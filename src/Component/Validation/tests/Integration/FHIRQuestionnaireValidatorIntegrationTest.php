<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireValidator;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationReport;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRReferenceResolver;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRFixedValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPathInvariantValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPatternValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRTargetProfileValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validation;

/**
 * Validates real deserialized Questionnaire/QuestionnaireResponse fixtures end to end,
 * including merging the questionnaire validator's report with FHIRValidationService output.
 */
final class FHIRQuestionnaireValidatorIntegrationTest extends TestCase
{
    private const FIXTURE_DIR = __DIR__ . '/Fixture/Questionnaire';

    private FHIRSerializationService $serializer;

    private FHIRQuestionnaireValidator $validator;

    protected function setUp(): void
    {
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
        $this->validator  = new FHIRQuestionnaireValidator();
    }

    private function loadFixture(string $name, string $targetClass): object
    {
        $json = file_get_contents(self::FIXTURE_DIR . '/' . $name);
        self::assertNotFalse($json, "Fixture {$name} must be readable");

        return $this->serializer->deserialize($json, $targetClass);
    }

    private function questionnaire(): QuestionnaireResource
    {
        $questionnaire = $this->loadFixture('bp-questionnaire.json', QuestionnaireResource::class);
        self::assertInstanceOf(QuestionnaireResource::class, $questionnaire);

        return $questionnaire;
    }

    private function response(string $fixture): QuestionnaireResponseResource
    {
        $response = $this->loadFixture($fixture, QuestionnaireResponseResource::class);
        self::assertInstanceOf(QuestionnaireResponseResource::class, $response);

        return $response;
    }

    /**
     * Mirrors the constraint wiring used by FHIRValidatorSpecificationTest so that the
     * generated models' custom constraint attributes resolve to their validators.
     */
    private static function createValidationService(): FHIRValidationService
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $registry = new FHIRValidationMessageRegistry();
        $pathSvc  = new FHIRPathService();
        $matcher  = new SliceDiscriminatorMatcher($accessor);
        $resolver = new NullFHIRReferenceResolver();

        $defaultFactory = new ConstraintValidatorFactory();
        $enumNamespace  = 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum';

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
                    $constraint instanceof FHIRProfileConstraint => new FHIRProfileConstraintValidator($this->accessor),
                    $constraint instanceof FHIRPathInvariant     => new FHIRPathInvariantValidator($this->pathSvc, $this->registry),
                    $constraint instanceof FHIRValueSetBinding   => new FHIRValueSetBindingValidator(
                        $this->registry,
                        [$this->enumNamespace],
                    ),
                    $constraint instanceof FHIRFixedValue        => new FHIRFixedValueValidator($this->registry),
                    $constraint instanceof FHIRPatternValue      => new FHIRPatternValueValidator($this->registry),
                    $constraint instanceof FHIRSliceConstraint   => new FHIRSliceConstraintValidator($this->accessor, $this->matcher),
                    $constraint instanceof FHIRTargetProfile     => new FHIRTargetProfileValidator($this->resolver, $this->registry),
                    default                                      => $this->default->getInstance($constraint),
                };
            }
        };

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setConstraintValidatorFactory($factory)
            ->getValidator();

        return new FHIRValidationService($validator, $pathSvc);
    }

    public function testValidResponsePassesValidation(): void
    {
        $report = $this->validator->validate($this->questionnaire(), $this->response('bp-response-valid.json'));

        self::assertTrue($report->isValid(), 'Valid blood-pressure response must produce no errors: '
            . implode('; ', array_map(static fn ($v) => $v->message, $report->violations)));
        self::assertSame([], $report->violations);
    }

    public function testResponseMissingRequiredItemProducesOneError(): void
    {
        $report = $this->validator->validate($this->questionnaire(), $this->response('bp-response-missing-required.json'));

        self::assertFalse($report->isValid());
        self::assertCount(1, $report->errors());
        self::assertStringContainsString("Required item 'systolic' has no answer", $report->errors()[0]->message);
    }

    public function testReportsMergeWithFHIRValidationServiceOutput(): void
    {
        $response = $this->response('bp-response-valid.json');

        $validationService = self::createValidationService();

        $baseReport          = $validationService->validate($response);
        $questionnaireReport = $this->validator->validate($this->questionnaire(), $response);

        $merged = new FHIRValidationReport([...$baseReport->violations, ...$questionnaireReport->violations]);

        self::assertCount(
            \count($baseReport->violations) + \count($questionnaireReport->violations),
            $merged->violations,
        );
        self::assertTrue($questionnaireReport->isValid());
    }
}
