<?php

declare(strict_types=1);

/**
 * Seed ardenexal outcome files from actual FHIRValidationService output.
 *
 * Run from the repository root:
 *   php src/Component/Validation/tests/Integration/seed-outcomes.php       # seeds R4
 *   php src/Component/Validation/tests/Integration/seed-outcomes.php r5    # seeds R5
 *   php src/Component/Validation/tests/Integration/seed-outcomes.php r4b   # seeds R4B
 *
 * Writes one JSON file per qualifying test case to outcomes/ardenexal/.
 * Existing files are overwritten. Known-gap violations are excluded from counts,
 * matching the logic in FHIRValidatorSpecificationTest::isKnownGap().
 *
 * Each file carries the three asserted counts, a `java` block holding the reference validator's
 * counts for the same case, and an `outcome` block holding the messages behind ours:
 *
 *   {
 *     "errorCount": 1, "warningCount": 0, "infoCount": 0,
 *     "java": {
 *       "errorCount": 1, "warningCount": 0, "infoCount": 0,
 *       "classification": "EQUAL", "warningClassification": "EQUAL",
 *       "errors": ["Not a valid date format: 'not a date'"]
 *     },
 *     "outcome": {
 *       "errors":             { "invariant:ref-1": ["Coverage.payor[0] — SHALL have a contained …"] },
 *       "suppressedErrors":   { "invariant:dom-3": ["(root) — …"] },
 *       "warnings":           {},
 *       "suppressedWarnings": {}
 *     }
 *   }
 *
 * The `suppressed*` keys are the point of the `outcome` block: isKnownGap() removes those violations
 * from the counts entirely, so without listing them a reviewer cannot tell a genuinely clean case from
 * one whose findings were filtered away. A count that reads right for the wrong reason is the failure
 * mode this catches — `R5.narrative-binary` reports errorCount 0 while hiding a real dom-3.
 *
 * The `java` block exists so divergence from the oracle is readable in the file itself, without
 * running compare-java-outcomes.php. **It is deliberately named `java`, not `expected`.** The
 * expectation this suite asserts is `errorCount` — our own seeded count. Java's number is evidence
 * about whether that expectation is *right*, and conflating the two invites "fixing" a BELOW case by
 * editing the seed, which is precisely the failure the oracle exists to prevent.
 *
 * `classification` is ours-vs-Java on errors (ABOVE = we report more, BELOW = fewer);
 * `warningClassification` is the same for warnings. `errors` lists Java's error/fatal issue texts.
 *
 * Two deliberate omissions, both to avoid widening JavaOutcome while it is load-bearing for
 * CaseComparison and ComparisonReport:
 *  - Java's *warning* and *info* texts. Warning divergence is already understood as structural (Java's
 *    warnings are largely terminology-server findings we cannot produce offline), so counts suffice.
 *  - The `expression` each Java issue is located at. JavaOutcome parses `details.text` only, so the
 *    texts here carry no element path. Reviewing *where* a difference sits still needs the oracle file
 *    at vendor/fhir/fhir-test-cases/validator/outcomes/java/. Worth adding when JavaOutcome is next
 *    touched.
 *
 * `"java": null` means **no oracle exists** for the case — the manifest declares no `java` key, or the
 * file is missing. That is not the same as Java finding nothing, which is a real result and encodes as
 * counts of 0. Never collapse the two: it manufactures phantom EQUAL cases out of no-oracle skips.
 *
 * Only `errorCount` and `warningCount` are asserted by FHIRValidatorSpecificationTest; the `java` and
 * `outcome` blocks are review evidence. Both are deterministic (sorted families, stable order) so an
 * unchanged validator re-seeds byte-identically.
 */

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRReferenceResolver;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\Classification;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\JavaOutcome;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\JavaOutcomeReader;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\ViolationFamilyClassifier;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRFixedValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPathInvariantValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPatternValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRTargetProfileValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

// matchetype: FHIRPath pattern-matching syntax tests using $instant$ placeholders — not real dateTime values.
const SKIP_MODULES = ['tx', 'cda', 'cdshooks', 'shc', 'matchetype'];
const OUTCOMES_DIR = __DIR__ . '/outcomes/ardenexal';

$versionArg  = strtolower($argv[1] ?? 'r4');
$fhirVersion = match ($versionArg) {
    'r4b'   => FhirVersion::R4B,
    'r5'    => FhirVersion::R5,
    default => FhirVersion::R4,
};
$outcomePrefix = match ($fhirVersion) {
    FhirVersion::R4B => 'R4B',
    FhirVersion::R5  => 'R5',
    default          => 'R4',
};

echo "Seeding {$outcomePrefix} outcomes…\n";

$vendorDir    = __DIR__ . '/../../../../../vendor';
$manifestPath = $vendorDir . '/fhir/fhir-test-cases/validator/manifest.json';

if (!file_exists($manifestPath)) {
    fwrite(STDERR, "ERROR: fhir/fhir-test-cases not installed. Run: composer update fhir/fhir-test-cases\n");
    exit(1);
}

$manifest = json_decode((string) file_get_contents($manifestPath), true);
$service  = createValidationService($fhirVersion);
$serial   = FHIRSerializationService::createDefault($fhirVersion);

// The harness's own oracle reader, reused rather than reimplemented. It handles both forms the
// manifest uses for its "java" key — an inline counts object, or a path to an OperationOutcome file —
// and returns null when there is no oracle at all. Reconstructing the path from our version prefix
// would be wrong: the manifest sometimes points a case at an oracle under a different version prefix.
$javaReader = new JavaOutcomeReader($vendorDir . '/fhir/fhir-test-cases/validator');

$written  = 0;
$skipped  = 0;
$errors   = 0;

$cases = deduplicateCases($manifest['test-cases'], $fhirVersion);

foreach ($cases as $name => $case) {
    $file = (string) ($case['file'] ?? '');
    if (!str_ends_with($file, '.json') && !str_ends_with($file, '.xml')) {
        ++$skipped;
        continue;
    }

    $filePath = $vendorDir . '/fhir/fhir-test-cases/validator/' . $file;
    if (!file_exists($filePath)) {
        ++$skipped;
        continue;
    }

    $data = file_get_contents($filePath);
    if ($data === false) {
        ++$skipped;
        continue;
    }

    try {
        $resource = $serial->deserialize($data);
    } catch (Throwable $e) {
        // Deserializer threw (bad format, bad XML, bad JSON, etc.).
        // If Java also expects errors, seed errorCount=1 so the spec test asserts
        // rather than staying Incomplete. If Java expects 0 errors (e.g. allow-comments
        // JSON5 that we can't parse), leave unseeded so the test stays Incomplete.
        $javaOutcome    = $javaReader->read($case);
        $javaErrorCount = $javaOutcome?->errorCount;
        if ($javaErrorCount === null || $javaErrorCount === 0) {
            echo "  SKIP (deserialize, java-clean) {$name}: {$e->getMessage()}\n";
            ++$skipped;
            continue;
        }

        // Same shape as the validated cases, so every file can be read the same way. There is no
        // validation report here — the payload never became a resource — so the single error is the
        // parse failure itself, and recording its message is what makes the file reviewable.
        $outcome = json_encode([
            'errorCount'   => 1,
            'warningCount' => 0,
            'infoCount'    => 0,
            // Java's counts for the same case. On a parse failure this is the most valuable place for
            // them: our single error is "we could not read the document", so a Java errorCount above 1
            // says the document is readable and we are missing checks, not that the fixture is invalid.
            'java'         => javaBlock($javaOutcome, ourErrorCount: 1, ourWarningCount: 0),
            'outcome'      => [
                'errors'             => (object) ['deserialization' => ['(root) — ' . $e->getMessage()]],
                'suppressedErrors'   => (object) [],
                'warnings'           => (object) [],
                'suppressedWarnings' => (object) [],
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
        $outFile = OUTCOMES_DIR . '/' . $outcomePrefix . '.' . sanitizeName($name) . '-base.json';
        if (file_put_contents($outFile, $outcome) !== false) {
            ++$written;
        } else {
            echo "  ERROR writing {$name}\n";
            ++$errors;
        }

        continue;
    }

    try {
        $report = $service->validate($resource);
    } catch (Error $e) {
        echo "  SKIP (validate error) {$name}: {$e->getMessage()}\n";
        ++$skipped;
        continue;
    }

    $countedErrors      = array_values(array_filter($report->errors(), fn ($v) => !isKnownGap($v, $resource)));
    $suppressedErrors   = array_values(array_filter($report->errors(), fn ($v) => isKnownGap($v, $resource)));
    $countedWarnings    = array_values(array_filter($report->warnings(), fn ($v) => !isKnownGap($v, $resource)));
    $suppressedWarnings = array_values(array_filter($report->warnings(), fn ($v) => isKnownGap($v, $resource)));

    $javaOutcome = $javaReader->read($case);

    $outcome = json_encode([
        'errorCount'   => count($countedErrors),
        'warningCount' => count($countedWarnings),
        'infoCount'    => count($report->info()),
        // The reference validator's counts for the same case, so divergence is readable here rather
        // than only via compare-java-outcomes.php. Evidence, never an assertion — see the file docblock
        // on why this is `java` and not `expected`.
        'java'         => javaBlock($javaOutcome, count($countedErrors), count($countedWarnings)),
        // The counts alone cannot be reviewed. This is the evidence behind them: every message that
        // produced a count, grouped by invariant key or constraint, plus the errors isKnownGap()
        // removed — those are invisible in errorCount and are exactly where a real defect can hide.
        // Cast to object so an empty group encodes as `{}` rather than `[]` — the three keys keep one
        // shape whether or not they hold anything, which matters for anything reading these files.
        'outcome'      => [
            'errors'             => (object) groupViolations($countedErrors),
            'suppressedErrors'   => (object) groupViolations($suppressedErrors),
            'warnings'           => (object) groupViolations($countedWarnings),
            'suppressedWarnings' => (object) groupViolations($suppressedWarnings),
        ],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";

    $outFile = OUTCOMES_DIR . '/' . $outcomePrefix . '.' . sanitizeName($name) . '-base.json';
    if (file_put_contents($outFile, $outcome) !== false) {
        ++$written;
    } else {
        echo "  ERROR writing {$name}\n";
        ++$errors;
    }
}

echo "Done. Written: {$written}, Skipped: {$skipped}, Errors: {$errors}\n";

// ── helpers ──────────────────────────────────────────────────────────────────

function sanitizeName(string $name): string
{
    return str_replace(['/', ' '], '-', $name);
}

/**
 * Group violations by family, each entry rendered as `path — message`.
 *
 * Family labels come from the comparison harness's classifier, so they match what
 * `compare-java-outcomes.php --family=<label>` accepts and a reviewer can pivot straight from a
 * seeded outcome to the side-by-side Java comparison for that family.
 *
 * Keys are sorted and encounter order is preserved within a family, so re-seeding an unchanged
 * validator produces a byte-identical file and the diff only ever shows real movement. The path is
 * included because "which element failed" is the first question when reviewing a count that moved.
 *
 * @param list<FHIRValidationViolation> $violations
 *
 * @return array<string, list<string>>
 */
function groupViolations(array $violations): array
{
    static $classifier = null;
    $classifier ??= new ViolationFamilyClassifier();

    $grouped = [];
    foreach ($violations as $violation) {
        $family = $classifier->classify($violation);
        $where  = $violation->path === '' ? '(root)' : $violation->path;

        $grouped[$family][] = $where . ' — ' . $violation->message;
    }

    ksort($grouped);

    return $grouped;
}

/**
 * Resolve the expected Java error count from either an inline object or an external file.
 *
 * @param array<string, mixed> $case
 */
/**
 * Build the `java` block: the reference validator's counts for this case, beside ours.
 *
 * Returns null when there is no oracle, which JavaOutcomeReader signals by returning null for both a
 * missing "java" manifest key and an unreadable outcome file. Encoding that as zero counts would
 * manufacture phantom EQUAL cases out of the no-oracle skips, so absence stays absent.
 *
 * `errors` is sorted so an unchanged oracle re-seeds byte-identically; Java's issue order is not
 * something we control and must not leak into the diff.
 *
 * @return array{errorCount: int, warningCount: int, infoCount: int, classification: string, warningClassification: string, errors: list<string>}|null
 */
function javaBlock(?JavaOutcome $java, int $ourErrorCount, int $ourWarningCount): ?array
{
    if ($java === null) {
        return null;
    }

    $errors = $java->errorTexts;
    sort($errors);

    return [
        'errorCount'            => $java->errorCount,
        'warningCount'          => $java->warningCount,
        'infoCount'             => $java->infoCount,
        'classification'        => Classification::compare($ourErrorCount, $java->errorCount)->value,
        'warningClassification' => Classification::compare($ourWarningCount, $java->warningCount)->value,
        'errors'                => array_values($errors),
    ];
}

/** @param list<array<string, mixed>> $testCases @return array<string, array<string, mixed>> */
function deduplicateCases(array $testCases, FhirVersion $version): array
{
    $out = [];
    foreach ($testCases as $case) {
        if (!matchesVersion($case, $version)) {
            continue;
        }
        if (($case['use-test'] ?? true) === false) {
            continue;
        }
        $m = $case['module'] ?? '';
        if (in_array($m, SKIP_MODULES, true)) {
            continue;
        }
        if (isset($case['supporting']) || isset($case['profiles'])) {
            continue;
        }
        if (($case['allow-comments'] ?? false) === true) {
            continue;
        }
        if (!isset($case['java'])) {
            continue;
        }
        $name       = (string) ($case['name'] ?? '');
        $out[$name] = $case;
    }

    return $out;
}

function matchesVersion(array $case, FhirVersion $version): bool
{
    $v = $case['version'] ?? null;

    return match ($version) {
        FhirVersion::R4  => $v === '4.0',
        FhirVersion::R4B => $v === '4.3',
        FhirVersion::R5  => $v === null || in_array($v, ['5.0', '5.0.0'], true),
    };
}

function isKnownGap(FHIRValidationViolation $v, object $resource): bool
{
    if (str_contains($v->message, 'has no generated enum class')) {
        return true;
    }
    if ($v->invariantKey === 'dom-3') {
        return true;
    }
    if ($v->invariantKey === 'sdf-19') {
        return true;
    }
    if ($v->constraintClass === NotBlank::class
        && $v->path !== ''
        && property_exists($resource, $v->path)
        && isset($resource->{$v->path})
        && $resource->{$v->path} === false) {
        return true;
    }

    return false;
}

function createValidationService(FhirVersion $version = FhirVersion::R4): FHIRValidationService
{
    $accessor       = PropertyAccess::createPropertyAccessor();
    $registry       = new FHIRValidationMessageRegistry();
    $pathSvc        = new FHIRPathService();
    $matcher        = new SliceDiscriminatorMatcher($accessor);
    $resolver       = new NullFHIRReferenceResolver();
    $defaultFactory = new ConstraintValidatorFactory();
    $enumNamespace  = "Ardenexal\\FHIRTools\\Component\\Models\\{$version->value}\\Enum";

    $factory = new class (
        $accessor,
        $registry,
        $pathSvc,
        $matcher,
        $resolver,
        $defaultFactory,
        $enumNamespace,
    ) implements ConstraintValidatorFactoryInterface {
        public function __construct(
            private readonly PropertyAccessorInterface $accessor,
            private readonly FHIRValidationMessageRegistry $registry,
            private readonly FHIRPathService $pathSvc,
            private readonly SliceDiscriminatorMatcher $matcher,
            private readonly NullFHIRReferenceResolver $resolver,
            private readonly ConstraintValidatorFactory $default,
            private readonly string $enumNamespace,
        ) {
        }

        public function getInstance(Constraint $constraint): ConstraintValidatorInterface
        {
            return match (true) {
                $constraint instanceof FHIRProfileConstraint  => new FHIRProfileConstraintValidator($this->accessor),
                $constraint instanceof FHIRPathInvariant      => new FHIRPathInvariantValidator($this->pathSvc, $this->registry),
                $constraint instanceof FHIRValueSetBinding    => new FHIRValueSetBindingValidator(
                    $this->registry,
                    [$this->enumNamespace],
                ),
                $constraint instanceof FHIRFixedValue         => new FHIRFixedValueValidator($this->registry),
                $constraint instanceof FHIRPatternValue       => new FHIRPatternValueValidator($this->registry),
                $constraint instanceof FHIRSliceConstraint    => new FHIRSliceConstraintValidator($this->accessor, $this->matcher),
                $constraint instanceof FHIRTargetProfile      => new FHIRTargetProfileValidator($this->resolver, $this->registry),
                default                                       => $this->default->getInstance($constraint),
            };
        }
    };

    $validator = Validation::createValidatorBuilder()
        ->enableAttributeMapping()
        ->setConstraintValidatorFactory($factory)
        ->getValidator();

    return new FHIRValidationService($validator, $pathSvc);
}
