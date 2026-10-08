<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRProfileGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition\CompositionSection;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CompositionResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Observation\ObservationComponent;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRFixedValueValidator;
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

    private const string BP_FIXTURE = __DIR__ . '/../../Fixtures/StructureDefinitions/ObservationWithComponentSlicing.json';

    private const string BP_PROFILE_URL = 'http://example.org/StructureDefinition/sliced-bp';

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

    /**
     * Without ids, a child carrying no sliceName belongs to the slice header that precedes it: the
     * differential lists each slice's children right after the slice.
     */
    public function testWithoutIdsEachSliceTakesTheChildThatFollowsIt(): void
    {
        $json = file_get_contents(self::FIXTURE);
        self::assertIsString($json);
        /** @var array{differential: array{element: list<array<string, mixed>>}} $sd */
        $sd = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        foreach ($sd['differential']['element'] as $i => $element) {
            unset($sd['differential']['element'][$i]['id']);
        }

        $codesBySlice = [];
        foreach ($this->attributeArguments($this->generateFrom($sd), FHIRSliceConstraint::class) as $slice) {
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

        $class = $this->generateFrom($sd);

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

    /**
     * A scalar type slice reaches a bare-PHP-scalar variant (valueBoolean holds a `bool`), which the
     * validator must still read as present, or `1..1` rejects every conforming resource.
     */
    public function testScalarChoiceTypeSliceValidatesAgainstTheHeldScalar(): void
    {
        $profileUrl = 'http://example.org/StructureDefinition/boolean-observation';
        $class      = $this->generateFrom([
            'resourceType'   => 'StructureDefinition',
            'url'            => $profileUrl,
            'name'           => 'BooleanObservation',
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
                        'id'        => 'Observation.value[x]:valueBoolean',
                        'path'      => 'Observation.value[x]',
                        'sliceName' => 'valueBoolean',
                        'min'       => 1,
                        'max'       => '1',
                    ],
                ],
            ],
        ]);

        $profileClass = $this->evalClass($class);

        self::assertSame([], $this->validateAgainstProfile(new $profileClass(value: true), $profileUrl));
        self::assertSame([], $this->validateAgainstProfile(new $profileClass(value: false), $profileUrl));
        self::assertSame(
            ['valueBoolean: This collection should contain exactly 1 element.|This collection should contain exactly 1 elements.'],
            $this->validateAgainstProfile(new $profileClass(value: 5), $profileUrl),
        );
    }

    /**
     * Rules beneath a slice travel on its FHIRSliceConstraint, relative to a slice item, instead of
     * being dropped or flattened onto `component`. The diastolic slice names its value through a
     * type slice (`value[x]:valueQuantity`), which lands on the variant key.
     */
    public function testRulesBeneathASliceTravelOnItsSliceConstraint(): void
    {
        $class = $this->generateFrom($this->loadFixture(self::BP_FIXTURE));

        self::assertSame(
            [['path' => 'component', 'constraint' => Count::class, 'options' => ['min' => 2], 'groups' => [self::BP_PROFILE_URL]]],
            $this->attributeArguments($class, FHIRProfileConstraint::class),
        );

        $rulesBySlice = [];
        foreach ($this->attributeArguments($class, FHIRSliceConstraint::class) as $slice) {
            self::assertIsString($slice['sliceName']);
            self::assertIsArray($slice['rules'] ?? null);
            $rulesBySlice[$slice['sliceName']] = array_map(
                static fn (array $rule): string => $rule['path'] . ' ' . $rule['constraint'],
                $slice['rules'],
            );
        }

        self::assertSame([
            'SystolicBP' => [
                'code ' . FHIRPatternValue::class,
                'value[x].system ' . Count::class,
                'value[x].system ' . FHIRFixedValue::class,
                'value[x].code ' . Count::class,
                'value[x].code ' . FHIRFixedValue::class,
            ],
            'DiastolicBP' => [
                'code ' . FHIRPatternValue::class,
                'valueQuantity ' . Count::class,
                'valueQuantity.system ' . Count::class,
                'valueQuantity.system ' . FHIRFixedValue::class,
            ],
        ], $rulesBySlice);
    }

    /**
     * Shaped like the R5 core `bp` profile: the discriminator path `code.coding.code` is reached
     * through a nested slice of `coding`. The discriminator looks through it, and the nested slice
     * travels in the component slice's rules, where it binds only that component's codings.
     */
    public function testDiscriminatorIsFoundThroughANestedSlice(): void
    {
        $class = $this->generateFrom([
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/resliced-bp',
            'name'           => 'ReslicedBloodPressure',
            'type'           => 'Observation',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Observation',
            'differential'   => [
                'element' => [
                    [
                        'id'      => 'Observation.component',
                        'path'    => 'Observation.component',
                        'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'code.coding.code']], 'rules' => 'open'],
                    ],
                    ['id' => 'Observation.component:SystolicBP', 'path' => 'Observation.component', 'sliceName' => 'SystolicBP', 'min' => 1, 'max' => '1'],
                    [
                        'id'      => 'Observation.component:SystolicBP.code.coding',
                        'path'    => 'Observation.component.code.coding',
                        'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'code']], 'rules' => 'open'],
                    ],
                    ['id' => 'Observation.component:SystolicBP.code.coding:SBPCode', 'path' => 'Observation.component.code.coding', 'sliceName' => 'SBPCode', 'min' => 1, 'max' => '1'],
                    ['id' => 'Observation.component:SystolicBP.code.coding:SBPCode.code', 'path' => 'Observation.component.code.coding.code', 'min' => 1, 'fixedCode' => '8480-6'],
                    ['id' => 'Observation.component:SystolicBP.valueQuantity.code', 'path' => 'Observation.component.valueQuantity.code', 'fixedCode' => 'mm[Hg]'],
                ],
            ],
        ]);

        $slices = array_values(array_filter(
            $this->attributeArguments($class, FHIRSliceConstraint::class),
            static fn (array $slice): bool => $slice['property'] === 'component',
        ));

        self::assertCount(1, $slices);
        self::assertSame('8480-6', $slices[0]['discriminatorValue'] ?? null);
        self::assertSame([
            ['path' => 'valueQuantity.code', 'constraint' => FHIRFixedValue::class, 'options' => ['value' => 'mm[Hg]']],
            ['path' => 'code.coding', 'constraint' => FHIRSlicingRules::class, 'options' => ['property' => 'code.coding', 'rules' => 'open']],
            [
                'path'       => 'code.coding',
                'constraint' => FHIRSliceConstraint::class,
                'options'    => [
                    'property'           => 'code.coding',
                    'sliceName'          => 'SBPCode',
                    'min'                => 1,
                    'max'                => 1,
                    'discriminatorType'  => 'value',
                    'discriminatorPath'  => 'code',
                    'discriminatorValue' => '8480-6',
                    'orderedIndex'       => 0,
                    'rules'              => [
                        ['path' => 'code', 'constraint' => Count::class, 'options' => ['min' => 1]],
                        ['path' => 'code', 'constraint' => FHIRFixedValue::class, 'options' => ['value' => '8480-6']],
                    ],
                ],
            ],
        ], $slices[0]['rules'] ?? null);
    }

    /**
     * Shaped like AU Core's blood pressure, which declares no slicing of its own: it adds a slice to
     * the parent's `code.coding` slicing, and narrows the parent's SystolicBP slice only through a
     * nested slice beneath it. Both slicings come from the snapshot.
     */
    public function testSlicesOnInheritedSlicingTakeTheirSlicingFromTheSnapshot(): void
    {
        $class = $this->generateFrom($this->inheritedSlicingProfile());

        self::assertSame([], $this->attributeArguments($class, FHIRSlicingRules::class), 'Inherited slicing rules stay with the parent');

        $slices = [];
        foreach ($this->attributeArguments($class, FHIRSliceConstraint::class) as $slice) {
            self::assertIsString($slice['property']);
            self::assertIsString($slice['sliceName']);
            $slices[$slice['property'] . ':' . $slice['sliceName']] = $slice;
        }

        self::assertSame(['code.coding:snomedBPCode', 'component:SystolicBP'], array_keys($slices));
        self::assertSame([1, 1, '75367002'], [$slices['code.coding:snomedBPCode']['min'], $slices['code.coding:snomedBPCode']['max'], $slices['code.coding:snomedBPCode']['discriminatorValue'] ?? null]);

        // Touched only through its children: no cardinality restated, and the parent's LOINC code,
        // not the SNOMED code added beneath it, still decides which component is systolic
        $systolic = $slices['component:SystolicBP'];
        self::assertSame([0, '*', '8480-6'], [$systolic['min'], $systolic['max'], $systolic['discriminatorValue'] ?? null]);
        self::assertIsArray($systolic['rules'] ?? null);
        self::assertCount(1, $systolic['rules']);
        self::assertSame(FHIRSliceConstraint::class, $systolic['rules'][0]['constraint']);
        self::assertSame('code.coding', $systolic['rules'][0]['path']);
        self::assertSame(['snomedSBP', 1, '271649006'], [
            $systolic['rules'][0]['options']['sliceName'],
            $systolic['rules'][0]['options']['min'],
            $systolic['rules'][0]['options']['discriminatorValue'] ?? null,
        ]);
    }

    /**
     * A profile restating its parent's slicing as closed is checked in its own group against every
     * slice in that group, so the parent's slices must be there too; and a closed slicing with no
     * slices at all must not be emitted, since it would reject every item.
     */
    public function testRestatedClosedSlicingCarriesTheParentsSlices(): void
    {
        $closed = ['discriminator' => [['type' => 'value', 'path' => 'code']], 'rules' => 'closed'];
        $class  = $this->generateFrom([
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/restated-closed',
            'name'           => 'RestatedClosed',
            'type'           => 'Observation',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Observation',
            'differential'   => [
                'element' => [
                    ['id' => 'Observation.code.coding', 'path' => 'Observation.code.coding', 'slicing' => $closed],
                    ['id' => 'Observation.code.coding:snomed', 'path' => 'Observation.code.coding', 'sliceName' => 'snomed', 'min' => 1, 'max' => '1'],
                    ['id' => 'Observation.code.coding:snomed.code', 'path' => 'Observation.code.coding.code', 'fixedCode' => '75367002'],
                    ['id' => 'Observation.component:SystolicBP.code.coding', 'path' => 'Observation.component.code.coding', 'slicing' => $closed],
                    ['id' => 'Observation.component:SystolicBP.valueQuantity.code', 'path' => 'Observation.component.valueQuantity.code', 'fixedCode' => 'mm[Hg]'],
                ],
            ],
            'snapshot' => [
                'element' => [
                    ['id' => 'Observation.code.coding:loinc.code', 'path' => 'Observation.code.coding.code', 'fixedCode' => '85354-9'],
                    ['id' => 'Observation.code.coding:snomed.code', 'path' => 'Observation.code.coding.code', 'fixedCode' => '75367002'],
                    ['id' => 'Observation.component', 'path' => 'Observation.component', 'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'code.coding.code']], 'rules' => 'open']],
                    ['id' => 'Observation.component:SystolicBP.code.coding:SBPCode.code', 'path' => 'Observation.component.code.coding.code', 'fixedCode' => '8480-6'],
                ],
            ],
        ]);

        $slices = [];
        foreach ($this->attributeArguments($class, FHIRSliceConstraint::class) as $slice) {
            self::assertIsString($slice['property']);
            self::assertIsString($slice['sliceName']);
            $slices[$slice['property'] . ':' . $slice['sliceName']] = $slice;
        }

        self::assertSame(['code.coding:snomed', 'code.coding:loinc', 'component:SystolicBP'], array_keys($slices));
        self::assertSame([0, '*', '85354-9'], [$slices['code.coding:loinc']['min'], $slices['code.coding:loinc']['max'], $slices['code.coding:loinc']['discriminatorValue'] ?? null]);
        self::assertSame(
            [['path' => 'valueQuantity.code', 'constraint' => FHIRFixedValue::class, 'options' => ['value' => 'mm[Hg]']]],
            $slices['component:SystolicBP']['rules'] ?? null,
            'A closed nested slicing with no slices emits no rules for it',
        );
    }

    /** Without a snapshot, inherited slicing cannot be found; the gap is reported, not swallowed. */
    public function testSlicesOnUnfindableSlicingAreReportedAsWarnings(): void
    {
        $sd = $this->inheritedSlicingProfile();
        unset($sd['snapshot']);

        $context = new BuilderContext();
        $context->addResource(
            'http://hl7.org/fhir/StructureDefinition/Observation',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource',
            new ClassType('ObservationResource', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource')),
        );
        $errors = new ErrorCollector();
        $class  = (new FHIRProfileGenerator())->generate($sd, 'R4', $context, new PhpNamespace(self::EVAL_NAMESPACE), $errors);

        self::assertSame([], $this->attributeArguments($class, FHIRSliceConstraint::class));
        self::assertSame(
            ['Observation.code.coding', 'Observation.component', 'Observation.component:SystolicBP.code.coding'],
            array_column($errors->getWarnings(), 'path'),
        );
    }

    public function testNestedSliceOnInheritedSlicingBindsOnlyItsComponent(): void
    {
        $profileClass = $this->evalClass($this->generateFrom($this->inheritedSlicingProfile()));
        $profileUrl   = 'http://example.org/StructureDefinition/inherited-slicing-bp';
        $code         = fn (string ...$codes): CodeableConcept => new CodeableConcept(coding: array_map(
            static fn (string $code): Coding => new Coding(
                system: new UriPrimitive(value: str_contains($code, '-') ? 'http://loinc.org' : 'http://snomed.info/sct'),
                code: new CodePrimitive(value: $code),
            ),
            $codes,
        ));

        self::assertSame([], $this->validateAgainstProfile(new $profileClass(
            code: $code('85354-9', '75367002'),
            component: [
                new ObservationComponent(code: $code('8480-6', '271649006')),
                new ObservationComponent(code: $code('8462-4')),
            ],
        ), $profileUrl));

        self::assertSame([
            'component[0].code.coding: Slice "snomedSBP" on "component[0].code.coding" requires at least 1 item(s), but 0 matched.',
        ], $this->validateAgainstProfile(new $profileClass(
            code: $code('85354-9', '75367002'),
            component: [new ObservationComponent(code: $code('8480-6'))],
        ), $profileUrl));
    }

    /**
     * Without ids, a slice's children carry neither an id nor a sliceName, so only their position
     * ties them to the slice; they must not fall back to plain rules on the unsliced path.
     */
    public function testWithoutIdsSliceChildrenEmitNoPlainRules(): void
    {
        $sd = $this->loadFixture(self::FIXTURE);
        self::assertIsArray($sd['differential']);
        self::assertIsArray($sd['differential']['element']);
        foreach (array_keys($sd['differential']['element']) as $i) {
            unset($sd['differential']['element'][$i]['id']);
        }

        $class = $this->generateFrom($sd);

        self::assertSame(
            [['path' => 'section', 'constraint' => Count::class, 'options' => ['min' => 1], 'groups' => [self::PROFILE_URL]]],
            $this->attributeArguments($class, FHIRProfileConstraint::class),
        );
        self::assertSame([['path' => 'section', 'groups' => [self::PROFILE_URL]]], $this->attributeArguments($class, FHIRProfileMustSupport::class));
    }

    /**
     * @return array<string, mixed>
     */
    private function inheritedSlicingProfile(): array
    {
        $loinc  = 'http://loinc.org';
        $snomed = 'http://snomed.info/sct';

        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/inherited-slicing-bp',
            'name'           => 'InheritedSlicingBp',
            'type'           => 'Observation',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Observation',
            'differential'   => [
                'element' => [
                    ['id' => 'Observation.code.coding', 'path' => 'Observation.code.coding', 'min' => 2],
                    ['id' => 'Observation.code.coding:snomedBPCode', 'path' => 'Observation.code.coding', 'sliceName' => 'snomedBPCode', 'min' => 1, 'max' => '1'],
                    ['id' => 'Observation.code.coding:snomedBPCode.system', 'path' => 'Observation.code.coding.system', 'min' => 1, 'fixedUri' => $snomed],
                    ['id' => 'Observation.code.coding:snomedBPCode.code', 'path' => 'Observation.code.coding.code', 'min' => 1, 'fixedCode' => '75367002'],
                    ['id' => 'Observation.component:SystolicBP', 'path' => 'Observation.component', 'sliceName' => 'SystolicBP'],
                    ['id' => 'Observation.component:SystolicBP.code.coding:snomedSBP', 'path' => 'Observation.component.code.coding', 'sliceName' => 'snomedSBP', 'min' => 1, 'max' => '1'],
                    ['id' => 'Observation.component:SystolicBP.code.coding:snomedSBP.code', 'path' => 'Observation.component.code.coding.code', 'min' => 1, 'fixedCode' => '271649006'],
                ],
            ],
            'snapshot' => [
                'element' => [
                    ['id' => 'Observation.code.coding', 'path' => 'Observation.code.coding', 'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'code']], 'rules' => 'open']],
                    ['id' => 'Observation.code.coding:BPCode.code', 'path' => 'Observation.code.coding.code', 'fixedCode' => '85354-9'],
                    ['id' => 'Observation.code.coding:snomedBPCode.code', 'path' => 'Observation.code.coding.code', 'fixedCode' => '75367002'],
                    ['id' => 'Observation.component', 'path' => 'Observation.component', 'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'code.coding.code']], 'rules' => 'open']],
                    ['id' => 'Observation.component:SystolicBP', 'path' => 'Observation.component', 'sliceName' => 'SystolicBP', 'min' => 1, 'max' => '1'],
                    ['id' => 'Observation.component:SystolicBP.code.coding', 'path' => 'Observation.component.code.coding', 'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'code']], 'rules' => 'open']],
                    ['id' => 'Observation.component:SystolicBP.code.coding:SBPCode.system', 'path' => 'Observation.component.code.coding.system', 'fixedUri' => $loinc],
                    ['id' => 'Observation.component:SystolicBP.code.coding:SBPCode.code', 'path' => 'Observation.component.code.coding.code', 'fixedCode' => '8480-6'],
                    ['id' => 'Observation.component:SystolicBP.code.coding:snomedSBP.code', 'path' => 'Observation.component.code.coding.code', 'fixedCode' => '271649006'],
                ],
            ],
        ];
    }

    public function testGeneratedProfileAcceptsAConformingBloodPressure(): void
    {
        $profileClass = $this->evalClass($this->generateFrom($this->loadFixture(self::BP_FIXTURE)));

        $observation = new $profileClass(component: [
            $this->component('8480-6', 'http://unitsofmeasure.org', 'mm[Hg]'),
            $this->component('8462-4', 'http://unitsofmeasure.org', 'mm[Hg]'),
        ]);

        self::assertSame([], $this->validateAgainstProfile($observation, self::BP_PROFILE_URL));
    }

    /** Each slice's rules reach only its own item, reported at that item's index. */
    public function testRulesBeneathASliceBindOnlyItsMatchingItems(): void
    {
        $profileClass = $this->evalClass($this->generateFrom($this->loadFixture(self::BP_FIXTURE)));

        $observation = new $profileClass(component: [
            $this->component('8462-4', 'http://example.org/units', 'mm[Hg]'),
            $this->component('8480-6', 'http://unitsofmeasure.org', 'mmHg'),
        ]);

        self::assertSame([
            'component[1].value[x].code: The value mmHg does not match the required fixed value mm[Hg].',
            'component[0].valueQuantity.system: The value http://example.org/units does not match the required fixed value http://unitsofmeasure.org.',
        ], $this->validateAgainstProfile($observation, self::BP_PROFILE_URL));
    }

    private function component(string $loinc, string $unitSystem, string $unitCode): ObservationComponent
    {
        return new ObservationComponent(
            code: new CodeableConcept(coding: [
                new Coding(system: new UriPrimitive(value: 'http://loinc.org'), code: new CodePrimitive(value: $loinc)),
            ]),
            value: new Quantity(
                value: '120',
                system: new UriPrimitive(value: $unitSystem),
                code: new CodePrimitive(value: $unitCode),
            ),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function loadFixture(string $file): array
    {
        $json = file_get_contents($file);
        self::assertIsString($json);
        /** @var array<string, mixed> $sd */
        $sd = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return $sd;
    }

    private function generate(): ClassType
    {
        $json = file_get_contents(self::FIXTURE);
        self::assertIsString($json);
        /** @var array<string, mixed> $sd */
        $sd = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return $this->generateFrom($sd);
    }

    /**
     * @param array<string, mixed> $sd Profile StructureDefinition constraining an R4 resource
     */
    private function generateFrom(array $sd): ClassType
    {
        $type    = (string) $sd['type'];
        $context = new BuilderContext();
        $context->addResource(
            'http://hl7.org/fhir/StructureDefinition/' . $type,
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource',
            new ClassType($type . 'Resource', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource')),
        );

        return (new FHIRProfileGenerator())->generate($sd, 'R4', $context, new PhpNamespace(self::EVAL_NAMESPACE));
    }

    /**
     * Prints and evals the generated Composition profile class once per process.
     *
     * @return class-string<CompositionResource>
     */
    private function evalGeneratedProfile(): string
    {
        /** @var class-string<CompositionResource> */
        return $this->evalClass($this->generate());
    }

    /**
     * Prints and evals a generated profile class once per process.
     *
     * @return class-string
     */
    private function evalClass(ClassType $class): string
    {
        /** @var class-string $fqcn */
        $fqcn = self::EVAL_NAMESPACE . '\\' . $class->getName();

        if (!class_exists($fqcn, false)) {
            $namespace = new PhpNamespace(self::EVAL_NAMESPACE);
            eval('namespace ' . self::EVAL_NAMESPACE . ";\n" . (new Printer())->printClass($class, $namespace));
        }

        return $fqcn;
    }

    /**
     * Runs only the profile's validation group, so base-model rules on the resource stay out of it.
     *
     * @return list<string> "path: message" per violation
     */
    private function validateAgainstProfile(object $resource, string $profileUrl = self::PROFILE_URL): array
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
                    $constraint instanceof FHIRFixedValue        => new FHIRFixedValueValidator($this->registry),
                    default                                      => $this->default->getInstance($constraint),
                };
            }
        };

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setConstraintValidatorFactory($factory)
            ->getValidator();

        $messages = [];
        foreach ($validator->validate($resource, null, [$profileUrl]) as $violation) {
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
