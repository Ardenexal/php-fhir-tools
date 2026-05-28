<?php

declare(strict_types=1);

/**
 * Seed ardenexal outcome files from actual FHIRValidationService output.
 *
 * Run from the repository root:
 *   php src/Component/Validation/tests/Integration/seed-outcomes.php
 *
 * Writes one JSON file per qualifying R4 test case to:
 *   src/Component/Validation/tests/Integration/outcomes/ardenexal/R4.<name>-base.json
 *
 * Existing files are overwritten. Known-gap violations are excluded from counts,
 * matching the logic in FHIRValidatorSpecificationTest::isKnownGap().
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

const SKIP_MODULES = ['tx', 'cda', 'cdshooks', 'shc'];
const OUTCOMES_DIR = __DIR__ . '/outcomes/ardenexal';

$vendorDir    = __DIR__ . '/../../../../../vendor';
$manifestPath = $vendorDir . '/fhir/fhir-test-cases/validator/manifest.json';

if (!file_exists($manifestPath)) {
    fwrite(STDERR, "ERROR: fhir/fhir-test-cases not installed. Run: composer update fhir/fhir-test-cases\n");
    exit(1);
}

$manifest = json_decode((string) file_get_contents($manifestPath), true);
$service  = createValidationService();
$serial   = FHIRSerializationService::createDefault(FhirVersion::R4);

$written  = 0;
$skipped  = 0;
$errors   = 0;

$cases = deduplicateCases($manifest['test-cases']);

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
        echo "  SKIP (deserialize) {$name}: {$e->getMessage()}\n";
        ++$skipped;
        continue;
    }

    try {
        $report = $service->validate($resource);
    } catch (Error $e) {
        echo "  SKIP (validate error) {$name}: {$e->getMessage()}\n";
        ++$skipped;
        continue;
    }

    $errorCount   = count(array_filter($report->errors(), fn ($v) => !isKnownGap($v, $resource)));
    $warningCount = count(array_filter($report->warnings(), fn ($v) => !isKnownGap($v, $resource)));
    $infoCount    = count($report->info());

    $outcome = json_encode([
        'errorCount'   => $errorCount,
        'warningCount' => $warningCount,
        'infoCount'    => $infoCount,
    ], JSON_PRETTY_PRINT) . "\n";

    $outFile = OUTCOMES_DIR . '/R4.' . sanitizeName($name) . '-base.json';
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

/** @param list<array<string, mixed>> $testCases @return array<string, array<string, mixed>> */
function deduplicateCases(array $testCases): array
{
    $out = [];
    foreach ($testCases as $case) {
        $v = $case['version'] ?? '';
        if ($v !== '4.0') {
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
        if (!isset($case['java'])) {
            continue;
        }
        $name       = (string) ($case['name'] ?? '');
        $out[$name] = $case;
    }

    return $out;
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

function createValidationService(): FHIRValidationService
{
    $accessor       = PropertyAccess::createPropertyAccessor();
    $registry       = new FHIRValidationMessageRegistry();
    $pathSvc        = new FHIRPathService();
    $matcher        = new SliceDiscriminatorMatcher($accessor);
    $resolver       = new NullFHIRReferenceResolver();
    $defaultFactory = new ConstraintValidatorFactory();

    $factory = new class (
        $accessor,
        $registry,
        $pathSvc,
        $matcher,
        $resolver,
        $defaultFactory,
    ) implements ConstraintValidatorFactoryInterface {
        public function __construct(
            private readonly PropertyAccessorInterface $accessor,
            private readonly FHIRValidationMessageRegistry $registry,
            private readonly FHIRPathService $pathSvc,
            private readonly SliceDiscriminatorMatcher $matcher,
            private readonly NullFHIRReferenceResolver $resolver,
            private readonly ConstraintValidatorFactory $default,
        ) {
        }

        public function getInstance(Constraint $constraint): ConstraintValidatorInterface
        {
            return match (true) {
                $constraint instanceof FHIRProfileConstraint  => new FHIRProfileConstraintValidator($this->accessor),
                $constraint instanceof FHIRPathInvariant      => new FHIRPathInvariantValidator($this->pathSvc, $this->registry),
                $constraint instanceof FHIRValueSetBinding    => new FHIRValueSetBindingValidator(
                    $this->registry,
                    ['Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum'],
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
