<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Runs every applicable fhir-test-cases validator case and compares our counts against Java's.
 *
 * This is the instrument the nested-cascade plan reports against. It answers one question — "is
 * this change closer to the Java reference validator or further away?" — which the specification
 * suite cannot answer, because that suite asserts against expectations seeded from our own output.
 *
 * Case selection mirrors FHIRValidatorSpecificationTest so the two stay comparable. Cases with no
 * Java outcome are excluded rather than counted as zero-error: absent oracle is not agreement.
 */
final class ComparisonHarness
{
    /** Modules needing external services or out of scope, matching the specification suite. */
    private const SKIP_MODULES = ['tx', 'cda', 'cdshooks', 'shc', 'matchetype'];

    private readonly ViolationFamilyClassifier $familyClassifier;

    public function __construct(
        private readonly string $vendorDir,
        private readonly FHIRValidationService $validation,
        private readonly FHIRSerializationService $serialization,
        private readonly FhirVersion $version,
        ?ViolationFamilyClassifier $familyClassifier = null,
    ) {
        $this->familyClassifier = $familyClassifier ?? new ViolationFamilyClassifier();
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

        $start = microtime(true);

        foreach ($this->selectCases($manifest['test-cases']) as $name => $case) {
            $javaOutcome = $reader->read($case);
            if ($javaOutcome === null) {
                $skips[$name] = SkipReason::NoOracle;
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

            $comparison = $this->compareCase($name, $data, $javaOutcome, $skips);
            if ($comparison === null) {
                continue;
            }

            $comparisons[] = $comparison;
        }

        return new ComparisonReport(
            comparisons: $comparisons,
            wallClockSeconds: microtime(true) - $start,
            skips: $skips,
        );
    }

    /**
     * Validate one case and pair the result with its Java outcome.
     *
     * Returns null when the case cannot be validated at all, recording why in $skips. A case we
     * cannot run is not a case we agree on — and because dropping one silently *lowers* the ABOVE
     * count, every drop has to be attributable.
     *
     * @param array<string, SkipReason> $skips written by reference so the caller can account for drops
     */
    private function compareCase(
        string $name,
        string $data,
        JavaOutcome $javaOutcome,
        array &$skips,
    ): ?CaseComparison {
        try {
            $resource = $this->serialization->deserialize($data);
        } catch (\Throwable) {
            $skips[$name] = SkipReason::DeserializeThrew;

            return null;
        }

        if (!is_object($resource)) {
            $skips[$name] = SkipReason::DeserializeThrew;

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

        $allErrors      = $report->errors();
        $filteredErrors = array_values(
            array_filter($allErrors, fn (FHIRValidationViolation $v): bool => !self::isKnownGap($v, $resource)),
        );
        $filteredWarnings = array_values(
            array_filter($report->warnings(), fn (FHIRValidationViolation $v): bool => !self::isKnownGap($v, $resource)),
        );

        return new CaseComparison(
            name: $name,
            ourErrorCount: count($filteredErrors),
            ourErrorCountUnfiltered: count($allErrors),
            ourWarningCount: count($filteredWarnings),
            javaErrorCount: $javaOutcome->errorCount,
            javaWarningCount: $javaOutcome->warningCount,
            ourErrorMessages: array_map(
                static fn (FHIRValidationViolation $v): string => $v->message,
                $filteredErrors,
            ),
            families: $this->familyClassifier->classifyAll($filteredErrors),
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

    /**
     * Mirrors FHIRValidatorSpecificationTest::isKnownGap().
     *
     * Kept in sync deliberately rather than shared: the harness must be able to report what the
     * suite suppresses, which means reproducing the suite's rule rather than inheriting it.
     */
    public static function isKnownGap(FHIRValidationViolation $v, object $resource): bool
    {
        if (str_contains($v->message, 'has no generated enum class')) {
            return true;
        }

        if ($v->invariantKey === 'dom-3' || $v->invariantKey === 'sdf-19') {
            return true;
        }

        return $v->constraintClass === NotBlank::class
            && $v->path !== ''
            && property_exists($resource, $v->path)
            && isset($resource->{$v->path})
            && $resource->{$v->path} === false;
    }
}
