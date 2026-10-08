<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRProfileGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition\CompositionSection;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CompositionResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPatternValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Validation;

/**
 * Slice elements keep the unsliced `path` and name their slice only in `sliceName` (header) or
 * `id` (children), which is how SUSHI and the IG publisher write them. Their rules must reach
 * only #[FHIRSliceConstraint], never a plain #[FHIRProfileConstraint] on the unsliced path.
 *
 * @see https://github.com/Ardenexal/php-fhir-tools/issues/139
 */
final class FHIRProfileSliceChildEmissionTest extends TestCase
{
    private const string FIXTURE = __DIR__ . '/../../Fixtures/StructureDefinitions/CompositionWithSectionSlicing.json';

    private const string PROFILE_URL = 'http://example.org/StructureDefinition/sliced-composition';

    private const string EVAL_NAMESPACE = 'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Tests\\Eval\\Issue139';

    private const string CODE_SYSTEM = 'http://example.org/cs';

    public function testSliceElementsEmitNoProfileConstraintOnUnslicedPath(): void
    {
        $constraints = $this->attributeArguments($this->generate(), FHIRProfileConstraint::class);

        // Only the slicing header's own `section 1..*` survives; no per-slice Count or pattern.
        self::assertSame(
            [['path' => 'section', 'constraint' => Count::class, 'options' => ['min' => 1], 'groups' => [self::PROFILE_URL]]],
            $constraints,
        );
    }

    public function testSliceElementsEmitNoMustSupportOnUnslicedPath(): void
    {
        $mustSupport = $this->attributeArguments($this->generate(), FHIRProfileMustSupport::class);

        self::assertSame([['path' => 'section', 'groups' => [self::PROFILE_URL]]], $mustSupport);
    }

    public function testEachSliceTakesItsOwnDiscriminatorValueFromIdScopedChild(): void
    {
        $slices = $this->attributeArguments($this->generate(), FHIRSliceConstraint::class);

        $codesBySlice = [];
        foreach ($slices as $slice) {
            self::assertIsString($slice['sliceName']);
            self::assertIsArray($slice['discriminatorValue'] ?? null);
            $codesBySlice[$slice['sliceName']] = $slice['discriminatorValue']['coding'][0]['code'] ?? null;
        }

        self::assertSame(
            ['overview' => 'overview', 'forbidden' => 'forbidden', 'optional' => 'optional'],
            $codesBySlice,
        );
    }

    public function testGeneratedProfileAcceptsResourceWithOnlyRequiredSlice(): void
    {
        $profileClass = $this->evalGeneratedProfile();

        $violations = $this->validateAgainstProfile(new $profileClass(section: [$this->section('overview')]));

        self::assertSame([], $violations);
    }

    public function testGeneratedProfileRejectsSectionMatchingForbiddenSlice(): void
    {
        $profileClass = $this->evalGeneratedProfile();

        $violations = $this->validateAgainstProfile(new $profileClass(section: [
            $this->section('overview'),
            $this->section('forbidden'),
        ]));

        self::assertSame(
            ['section: Slice "forbidden" on "section" allows at most 0 item(s), but 1 matched.'],
            $violations,
        );
    }

    public function testChoiceTypeSliceRulesLandOnTheVariantPath(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/quantity-observation',
            'name'           => 'QuantityObservation',
            'type'           => 'Observation',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Observation',
            'differential'   => [
                'element' => [
                    [
                        'id'      => 'Observation.value[x]',
                        'path'    => 'Observation.value[x]',
                        'slicing' => ['discriminator' => [['type' => 'type', 'path' => '$this']], 'rules' => 'open'],
                    ],
                    [
                        'id'          => 'Observation.value[x]:valueQuantity',
                        'path'        => 'Observation.value[x]',
                        'sliceName'   => 'valueQuantity',
                        'min'         => 1,
                        'max'         => '1',
                        'mustSupport' => true,
                    ],
                    [
                        'id'       => 'Observation.value[x]:valueQuantity.system',
                        'path'     => 'Observation.value[x].system',
                        'fixedUri' => 'http://unitsofmeasure.org',
                    ],
                ],
            ],
        ];

        $context = new BuilderContext();
        $context->addResource(
            'http://hl7.org/fhir/StructureDefinition/Observation',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource',
            new ClassType('ObservationResource', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource')),
        );
        $class = (new FHIRProfileGenerator())->generate($sd, 'R4', $context, new PhpNamespace(self::EVAL_NAMESPACE));

        $paths = array_map(
            static fn (array $args): string => $args['path'] . ' ' . $args['constraint'],
            $this->attributeArguments($class, FHIRProfileConstraint::class),
        );
        self::assertSame(['valueQuantity ' . Count::class, 'valueQuantity.system ' . FHIRFixedValue::class], $paths);

        self::assertSame(
            [['path' => 'valueQuantity', 'groups' => ['http://example.org/StructureDefinition/quantity-observation']]],
            $this->attributeArguments($class, FHIRProfileMustSupport::class),
        );
    }

    private function generate(): ClassType
    {
        $context = new BuilderContext();
        $context->addResource(
            'http://hl7.org/fhir/StructureDefinition/Composition',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource',
            new ClassType('CompositionResource', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource')),
        );

        $json = file_get_contents(self::FIXTURE);
        self::assertIsString($json);
        /** @var array<string, mixed> $sd */
        $sd = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return (new FHIRProfileGenerator())->generate($sd, 'R4', $context, new PhpNamespace(self::EVAL_NAMESPACE));
    }

    /**
     * Prints and evals the generated profile class once per process.
     *
     * @return class-string<CompositionResource>
     */
    private function evalGeneratedProfile(): string
    {
        $class = $this->generate();
        /** @var class-string<CompositionResource> $fqcn */
        $fqcn = self::EVAL_NAMESPACE . '\\' . $class->getName();

        if (!class_exists($fqcn, false)) {
            $namespace = new PhpNamespace(self::EVAL_NAMESPACE);
            eval('namespace ' . self::EVAL_NAMESPACE . ";\n" . (new Printer())->printClass($class, $namespace));
        }

        return $fqcn;
    }

    /**
     * Runs only the profile's validation group, so base-model rules on Composition stay out of it.
     *
     * @return list<string> "path: message" per violation
     */
    private function validateAgainstProfile(object $resource): array
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $registry = new FHIRValidationMessageRegistry();
        $default  = new ConstraintValidatorFactory();

        $factory = new class ($accessor, $registry, $default) implements ConstraintValidatorFactoryInterface {
            public function __construct(
                private readonly PropertyAccessorInterface $accessor,
                private readonly FHIRValidationMessageRegistry $registry,
                private readonly ConstraintValidatorFactory $default,
            ) {
            }

            public function getInstance(Constraint $constraint): ConstraintValidatorInterface
            {
                return match (true) {
                    $constraint instanceof FHIRProfileConstraint => new FHIRProfileConstraintValidator($this->accessor),
                    $constraint instanceof FHIRSliceConstraint   => new FHIRSliceConstraintValidator(
                        $this->accessor,
                        new SliceDiscriminatorMatcher($this->accessor),
                    ),
                    $constraint instanceof FHIRPatternValue      => new FHIRPatternValueValidator($this->registry),
                    default                                      => $this->default->getInstance($constraint),
                };
            }
        };

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setConstraintValidatorFactory($factory)
            ->getValidator();

        $messages = [];
        foreach ($validator->validate($resource, null, [self::PROFILE_URL]) as $violation) {
            $messages[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
        }

        return $messages;
    }

    private function section(string $code): CompositionSection
    {
        return new CompositionSection(code: new CodeableConcept(coding: [
            new Coding(system: new UriPrimitive(value: self::CODE_SYSTEM), code: new CodePrimitive(value: $code)),
        ]));
    }

    /**
     * @param class-string $attributeClass
     *
     * @return list<array<string, mixed>>
     */
    private function attributeArguments(ClassType $class, string $attributeClass): array
    {
        $results = [];
        foreach ($class->getAttributes() as $attribute) {
            if ($attribute->getName() === $attributeClass) {
                /** @var array<string, mixed> $args */
                $args      = $attribute->getArguments();
                $results[] = $args;
            }
        }

        return $results;
    }
}
