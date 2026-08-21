<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRConformanceViolationException;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRUnreadableDocumentException;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;

/**
 * Runs every applicable fhir-test-cases validator case and compares our counts against Java's.
 *
 * This is the instrument the nested-cascade plan reports against. It answers one question — "is
 * this change closer to the Java reference validator or further away?" — which the specification
 * suite cannot answer, because that suite asserts against expectations seeded from our own output.
 *
 * Case selection mirrors FHIRValidatorSpecificationTest so the two stay comparable. Cases with no
 * Java outcome are excluded rather than counted as zero-error: absent oracle is not agreement.
 *
 * ## No suppression filter
 *
 * Our violations are compared exactly as the validator reports them. Java's counts are never
 * filtered, so filtering ours would break the comparison in the direction that matters most: a
 * suppressed violation reads as agreement.
 *
 * **Do not add a message- or invariant-keyed filter.** That shape hides findings the reference
 * validator agrees with, and rots invisibly — nothing tells you whether such a rule still matches
 * anything. Limitations we cannot close offline are declared per case, with both sides' counts
 * pinned, in {@see DeclaredLimitations}, where one that stops being a limitation fails its pin
 * rather than lingering.
 */
final class ComparisonHarness
{
    /** Modules needing external services or out of scope, matching the specification suite. */
    private const SKIP_MODULES = ['tx', 'cda', 'cdshooks', 'shc', 'matchetype'];

    private readonly ViolationFamilyClassifier $familyClassifier;

    /** Decides which reference findings we already report in different words. */
    private readonly JavaFindingMatcher $matcher;

    /** Names the capability each unpaired reference finding would need. */
    private readonly MissingFindingClassifier $missingClassifier;

    public function __construct(
        private readonly string $vendorDir,
        private readonly FHIRValidationService $validation,
        private readonly FHIRSerializationService $serialization,
        private readonly FhirVersion $version,
        ?ViolationFamilyClassifier $familyClassifier = null,
        ?JavaFindingMatcher $matcher = null,
        ?MissingFindingClassifier $missingClassifier = null,
    ) {
        $this->familyClassifier  = $familyClassifier  ?? new ViolationFamilyClassifier();
        $this->matcher           = $matcher           ?? new JavaFindingMatcher();
        $this->missingClassifier = $missingClassifier ?? new MissingFindingClassifier();
    }

    /**
     * Pair both sides' findings and label whatever is left over.
     *
     * Runs for every compared case, not only the `BELOW` ones: an `EQUAL` case can report one error each
     * while the two errors are about different things, and that is a missing finding the class cannot see.
     *
     * @param list<string>                  $javaErrorTexts
     * @param list<FHIRValidationViolation> $ourErrors
     * @param list<string>                  $javaExpressions where each reference finding was found,
     *                                                       parallel to $javaErrorTexts. Without it the
     *                                                       cardinality rule cannot tell two instances
     *                                                       of one type apart, and refuses to pair.
     */
    private function delta(array $javaErrorTexts, array $ourErrors, array $javaExpressions = []): FindingDelta
    {
        $missing = $this->matcher->unmatched($javaErrorTexts, $ourErrors, $javaExpressions);

        return new FindingDelta(
            $missing,
            $this->missingClassifier->classifyAll($missing),
            // A finding naming a code system nothing vendored can decide is a declared limitation, not work.
            array_map(static fn (string $t): ?string => DeclaredLimitations::reasonFor($t), $missing),
            // Kept so `audit-pairings.php` can review the pairings this count was produced from, rather
            // than a second run of the matcher that might not agree with it.
            $this->matcher->matchedPairs($javaErrorTexts, $ourErrors, $javaExpressions),
        );
    }

    /**
     * Our finding as a violation, for a rejection raised before the validator ran.
     *
     * The deserializer reports a finding as a bare string, but pairing compares violations. Wrapping it
     * keeps one type through the matcher instead of giving these two cases their own pairing path — they
     * are the cases most likely to duplicate a reference finding verbatim, so they need the real rules.
     */
    private function deserializationFinding(string $finding, ?string $code = null): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: 'error',
            path: '',
            message: $finding,
            constraintClass: '',
            profileGroup: null,
            invariantKey: null,
            code: $code,
        );
    }

    public function run(): ComparisonReport
    {
        $validatorDir = $this->vendorDir . '/fhir/fhir-test-cases/validator';
        $manifestPath = $validatorDir . '/manifest.json';

        if (!file_exists($manifestPath)) {
            throw new \RuntimeException("fhir/fhir-test-cases not installed at {$manifestPath}. Run: composer install");
        }

        $raw = file_get_contents($manifestPath);
        if ($raw === false) {
            throw new \RuntimeException("Cannot read manifest at {$manifestPath}");
        }

        $manifest = json_decode($raw, true);
        if (!is_array($manifest) || !is_array($manifest['test-cases'] ?? null)) {
            throw new \RuntimeException('Manifest is not in the expected shape');
        }

        $reader      = new JavaOutcomeReader($validatorDir);
        $comparisons = [];
        $skips       = [];
        $unread      = [];

        $start = microtime(true);

        foreach ($this->selectCases($manifest['test-cases']) as $name => $case) {
            $javaOutcome = $reader->read($case);
            if ($javaOutcome === null) {
                $skips[$name] = SkipReason::NoOracle;
                continue;
            }

            // `validateContains` selects the reference validator's contained-resource validation
            // policy. `IGNORE` means it did not validate contained resources at all, so its zero-error
            // outcome describes a configuration we have no way to reproduce — every constraint on a
            // contained resource is reachable for us the moment the resource is typed. Comparing our
            // run against it measures the option, not our correctness.
            //
            // Recorded as a skip rather than dropped in selectCases(), so the case stays visible in
            // the skip arithmetic instead of silently lowering the compared set. Its twin
            // (`CHECK_VALID`, same fixture) stays in the comparison and measures the same behaviour.
            if (($case['validateContains'] ?? null) === 'IGNORE') {
                $skips[$name] = SkipReason::UnmodelledOption;
                continue;
            }

            $file     = (string) ($case['file'] ?? '');
            $filePath = $validatorDir . '/' . $file;

            if ((!str_ends_with($file, '.json') && !str_ends_with($file, '.xml')) || !file_exists($filePath)) {
                $skips[$name] = SkipReason::Unreadable;
                continue;
            }

            $data = file_get_contents($filePath);
            if ($data === false) {
                $skips[$name] = SkipReason::Unreadable;
                continue;
            }

            $comparison = $this->compareCase($name, $data, $javaOutcome, $skips, $unread);
            if ($comparison === null) {
                continue;
            }

            $comparisons[] = $comparison;
        }

        return new ComparisonReport(
            comparisons: $comparisons,
            wallClockSeconds: microtime(true) - $start,
            skips: $skips,
            unread: $unread,
        );
    }

    /**
     * Validate one case and pair the result with its Java outcome.
     *
     * Returns null when the case cannot be validated at all, recording why in $skips. A case we
     * cannot run is not a case we agree on — and because dropping one silently *lowers* the ABOVE
     * count, every drop has to be attributable.
     *
     * @param array<string, SkipReason> $skips  written by reference so the caller can account for drops
     * @param list<UnreadCase>          $unread written by reference; deserialization failures paired
     *                                          with the Java outcome they were never compared against
     */
    private function compareCase(
        string $name,
        string $data,
        JavaOutcome $javaOutcome,
        array &$skips,
        array &$unread,
    ): ?CaseComparison {
        try {
            $resource = $this->serialization->deserialize($data);
        } catch (FHIRConformanceViolationException $e) {
            // Read, understood, and rejected on a stated FHIR rule — a finding, not an unread document.
            // Counting it 0 and filing it under UNREAD made a correct, Java-matching result read as a
            // BELOW gap: `bundle-dual-subject` emits `Composition.subject: max allowed = 1, but found 2`,
            // which is Java's error verbatim. It belongs in the comparison set with exactly one error.
            return new CaseComparison(
                name: $name,
                ourErrorCount: 1,
                ourWarningCount: 0,
                javaErrorCount: $javaOutcome->errorCount,
                javaWarningCount: $javaOutcome->warningCount,
                ourErrorMessages: [$e->finding],
                families: ['conformance:deserialization'],
                javaErrorTexts: $javaOutcome->errorTexts,
                ourErrorPaths: [''],
                delta: $this->delta(
                    $javaOutcome->errorTexts,
                    [$this->deserializationFinding($e->finding)],
                    $javaOutcome->errorExpressions,
                ),
            );
        } catch (FHIRUnreadableDocumentException $e) {
            // The bytes are not a document, and that is a finding rather than a silence. Java answers
            // every case in this class with an OperationOutcome error — `Unable to parse JSON`,
            // `Content is not allowed in prolog` — so counting ours 0 and filing it under UNREAD put
            // the case in no class at all: not ABOVE, not BELOW, invisible to any regression check.
            //
            // Only genuine parse/format failures reach here. "Unable to detect target class" keeps the
            // plain type and stays unread, because `R5.logicalxml-nonamespace` parses fine and Java
            // reports zero on it — reporting one would manufacture the project's first ABOVE case.
            return new CaseComparison(
                name: $name,
                ourErrorCount: 1,
                ourWarningCount: 0,
                javaErrorCount: $javaOutcome->errorCount,
                javaWarningCount: $javaOutcome->warningCount,
                ourErrorMessages: [$e->finding],
                families: ['unreadable:deserialization'],
                javaErrorTexts: $javaOutcome->errorTexts,
                ourErrorPaths: [''],
                delta: $this->delta(
                    $javaOutcome->errorTexts,
                    // Tagged so the matcher can recognise "we refused to read this document at all".
                    // The reference validator splits one unreadable document into several parse
                    // diagnostics — `json-no-quotes-2` gets three — and pairing those one-to-one would
                    // score two of them as checks we lack when we already reject the whole file.
                    [$this->deserializationFinding($e->finding, JavaFindingMatcher::UNREADABLE_DOCUMENT_CODE)],
                    $javaOutcome->errorExpressions,
                ),
            );
        } catch (\Throwable $e) {
            $this->recordUnread($name, $javaOutcome, $e->getMessage(), $skips, $unread);

            return null;
        }

        if (!is_object($resource)) {
            $this->recordUnread(
                $name,
                $javaOutcome,
                sprintf('deserialize() returned %s rather than an object', get_debug_type($resource)),
                $skips,
                $unread,
            );

            return null;
        }

        try {
            // \Throwable, not \Error: the cascade's known fatal (NoSuchMetadataException on a
            // non-object) extends Symfony's RuntimeException, so catching \Error would let it
            // escape and abort the whole run with no partial results.
            $report = $this->validation->validate($resource);
        } catch (\Throwable) {
            $skips[$name] = SkipReason::ValidateCrashed;

            return null;
        }

        $errors = $report->errors();

        return new CaseComparison(
            name: $name,
            ourErrorCount: count($errors),
            ourWarningCount: count($report->warnings()),
            javaErrorCount: $javaOutcome->errorCount,
            javaWarningCount: $javaOutcome->warningCount,
            ourErrorMessages: array_map(
                static fn (FHIRValidationViolation $v): string => $v->message,
                $errors,
            ),
            families: $this->familyClassifier->classifyAll($errors),
            javaErrorTexts: $javaOutcome->errorTexts,
            ourErrorPaths: array_map(
                static fn (FHIRValidationViolation $v): string => $v->path,
                $errors,
            ),
            delta: $this->delta($javaOutcome->errorTexts, $errors, $javaOutcome->errorExpressions),
        );
    }

    /**
     * @param array<int, mixed> $testCases
     *
     * @return array<string, array<string, mixed>> deduplicated by name; the last entry wins, matching
     *                                             the specification suite (later entries carry the real outcome file)
     */
    private function selectCases(array $testCases): array
    {
        $selected = [];

        foreach ($testCases as $case) {
            if (!is_array($case)) {
                continue;
            }

            if (($case['use-test'] ?? true) === false) {
                continue;
            }

            if (!$this->matchesVersion($case)) {
                continue;
            }

            if (in_array($case['module'] ?? '', self::SKIP_MODULES, true)) {
                continue;
            }

            // Dynamic StructureDefinition loading is not supported by the validator yet.
            if (isset($case['supporting']) || isset($case['profiles'])) {
                continue;
            }

            // JSON5 comment syntax, which our parser rejects.
            if (($case['allow-comments'] ?? false) === true) {
                continue;
            }

            $selected[(string) ($case['name'] ?? '')] = $case;
        }

        unset($selected['']);

        return $selected;
    }

    /**
     * Record a case we could not read, in both places it has to appear.
     *
     * The skip entry is kept as well as the UnreadCase, deliberately and non-negotiably: M02's exit
     * criterion and every re-baselining run compare `skipsByReason` against a committed baseline
     * (`R4 2/1/20/0`, `R5 2/0/34/0`). That arithmetic is the only thing that detects a case silently
     * leaving the comparison set — which lowers ABOVE and reads as an improvement. UNREAD is additive
     * enrichment on top of it, never a relocation of it.
     *
     * @param array<string, SkipReason> $skips
     * @param list<UnreadCase>          $unread
     */
    private function recordUnread(
        string $name,
        JavaOutcome $javaOutcome,
        string $failureMessage,
        array &$skips,
        array &$unread,
    ): void {
        $skips[$name] = SkipReason::DeserializeThrew;

        $unread[] = new UnreadCase(
            name: $name,
            javaErrorCount: $javaOutcome->errorCount,
            javaWarningCount: $javaOutcome->warningCount,
            failureMessage: $failureMessage,
            // Nothing of ours to pair against, so every reference finding is missing by definition. This
            // is what brings these cases into the missing-finding total rather than leaving them in a
            // footnote no check reads.
            delta: FindingDelta::allMissing($javaOutcome->errorTexts, $this->missingClassifier),
        );
    }

    /** @param array<string, mixed> $case */
    private function matchesVersion(array $case): bool
    {
        $declared = $case['version'] ?? null;

        return match ($this->version) {
            FhirVersion::R4  => $declared === '4.0',
            FhirVersion::R4B => $declared === '4.3',
            FhirVersion::R5  => $declared === null || in_array($declared, ['5.0', '5.0.0'], true),
        };
    }
}
