<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\CodeGeneration\tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRProfileGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that FHIRProfileGenerator emits #[FHIRSliceConstraint] and #[FHIRSlicingRules]
 * attributes from a profile differential that declares FHIR slicing.
 */
class FHIRProfileSliceEmissionTest extends TestCase
{
    private const string FIXTURES_DIR = __DIR__ . '/../../Fixtures/StructureDefinitions';

    private const string PROFILE_URL = 'http://example.org/StructureDefinition/patient-with-identifier-slicing';

    private FHIRProfileGenerator $generator;

    private BuilderContext $context;

    private PhpNamespace $namespace;

    protected function setUp(): void
    {
        $this->generator = new FHIRProfileGenerator();
        $this->context   = new BuilderContext();

        $patientClass = new ClassType('PatientResource', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource'));
        $this->context->addResource(
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource',
            $patientClass,
        );

        $this->namespace = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\Example\\Profile');
    }

    // ------------------------------------------------------------------
    // FHIRSlicingRules emission
    // ------------------------------------------------------------------

    public function testSlicingRulesAttributeEmittedForSlicedProperty(): void
    {
        $sd    = $this->loadFixture('PatientWithIdentifierSlicing.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $rulesAttrs = $this->getSlicingRulesAttributes($class);

        self::assertCount(1, $rulesAttrs, 'Expected exactly one FHIRSlicingRules attribute');

        $args = $rulesAttrs[0];
        self::assertSame('identifier', $args['property']);
        self::assertSame('open', $args['rules']);
        self::assertSame([self::PROFILE_URL], $args['groups']);
    }

    // ------------------------------------------------------------------
    // FHIRSliceConstraint emission – count and slice names
    // ------------------------------------------------------------------

    public function testTwoSliceConstraintAttributesEmittedForTwoSlices(): void
    {
        $sd    = $this->loadFixture('PatientWithIdentifierSlicing.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $sliceAttrs = $this->getSliceConstraintAttributes($class);

        self::assertCount(2, $sliceAttrs, 'Expected one FHIRSliceConstraint per defined slice');
    }

    public function testIhiNumberSliceConstraintHasCorrectCardinality(): void
    {
        $sd         = $this->loadFixture('PatientWithIdentifierSlicing.json');
        $class      = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $sliceAttrs = $this->getSliceConstraintAttributes($class);

        $ihiAttrs = array_values(array_filter(
            $sliceAttrs,
            static fn (array $args) => ($args['sliceName'] ?? '') === 'ihiNumber',
        ));

        self::assertCount(1, $ihiAttrs, 'Expected exactly one FHIRSliceConstraint for ihiNumber');

        $attr = $ihiAttrs[0];
        self::assertSame('identifier', $attr['property']);
        self::assertSame(1, $attr['min']);
        self::assertSame(1, $attr['max']);
        self::assertSame([self::PROFILE_URL], $attr['groups']);
    }

    public function testMedicareNumberSliceConstraintHasCorrectCardinality(): void
    {
        $sd         = $this->loadFixture('PatientWithIdentifierSlicing.json');
        $class      = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $sliceAttrs = $this->getSliceConstraintAttributes($class);

        $medicareAttrs = array_values(array_filter(
            $sliceAttrs,
            static fn (array $args) => ($args['sliceName'] ?? '') === 'medicareNumber',
        ));

        self::assertCount(1, $medicareAttrs);

        $attr = $medicareAttrs[0];
        self::assertSame(0, $attr['min']);
        self::assertSame(1, $attr['max']);
    }

    // ------------------------------------------------------------------
    // Discriminator value extraction
    // ------------------------------------------------------------------

    public function testIhiNumberSliceConstraintHasDiscriminatorValueFromChildFixedUri(): void
    {
        $sd         = $this->loadFixture('PatientWithIdentifierSlicing.json');
        $class      = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $sliceAttrs = $this->getSliceConstraintAttributes($class);

        $ihiAttr = array_values(array_filter(
            $sliceAttrs,
            static fn (array $args) => ($args['sliceName'] ?? '') === 'ihiNumber',
        ))[0] ?? [];

        self::assertSame('value', $ihiAttr['discriminatorType'] ?? null);
        self::assertSame('system', $ihiAttr['discriminatorPath'] ?? null);
        self::assertSame(
            'http://ns.electronichealth.net.au/id/hi/ihi/1.0',
            $ihiAttr['discriminatorValue'] ?? null,
        );
    }

    public function testMedicareNumberSliceConstraintHasDiscriminatorValueFromChildFixedUri(): void
    {
        $sd         = $this->loadFixture('PatientWithIdentifierSlicing.json');
        $class      = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $sliceAttrs = $this->getSliceConstraintAttributes($class);

        $medicareAttr = array_values(array_filter(
            $sliceAttrs,
            static fn (array $args) => ($args['sliceName'] ?? '') === 'medicareNumber',
        ))[0] ?? [];

        self::assertSame(
            'http://ns.electronichealth.net.au/id/medicare-number',
            $medicareAttr['discriminatorValue'] ?? null,
        );
    }

    // ------------------------------------------------------------------
    // No emission when no slicing present
    // ------------------------------------------------------------------

    public function testNoSliceAttributesEmittedWhenDifferentialHasNoSlicing(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/no-slicing',
            'name'           => 'NoSlicingProfile',
            'type'           => 'Patient',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Patient',
            'differential'   => [
                'element' => [
                    ['id' => 'Patient.name', 'path' => 'Patient.name', 'min' => 1, 'max' => '*'],
                ],
            ],
        ];

        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertEmpty($this->getSliceConstraintAttributes($class));
        self::assertEmpty($this->getSlicingRulesAttributes($class));
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /**
     * @return list<array<string, mixed>>
     */
    private function getSliceConstraintAttributes(ClassType $class): array
    {
        $results = [];
        foreach ($class->getAttributes() as $attribute) {
            if ($attribute->getName() === FHIRSliceConstraint::class) {
                /** @var array<string, mixed> $args */
                $args      = $attribute->getArguments();
                $results[] = $args;
            }
        }

        return $results;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getSlicingRulesAttributes(ClassType $class): array
    {
        $results = [];
        foreach ($class->getAttributes() as $attribute) {
            if ($attribute->getName() === FHIRSlicingRules::class) {
                /** @var array<string, mixed> $args */
                $args      = $attribute->getArguments();
                $results[] = $args;
            }
        }

        return $results;
    }

    /**
     * @return array<string, mixed>
     */
    private function loadFixture(string $filename): array
    {
        $path = self::FIXTURES_DIR . '/' . $filename;
        self::assertFileExists($path, "Fixture file not found: {$filename}");

        $json = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        self::assertIsArray($json);

        return $json;
    }
}
