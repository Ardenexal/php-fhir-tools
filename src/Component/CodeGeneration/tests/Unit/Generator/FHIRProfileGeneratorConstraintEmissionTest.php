<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\CodeGeneration\tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRProfileGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Count;

/**
 * Verifies that FHIRProfileGenerator emits #[FHIRProfileConstraint] attributes for differential
 * elements carrying cardinality, fixed, or pattern constraints.
 *
 * @see FHIRProfileGenerator
 */
class FHIRProfileGeneratorConstraintEmissionTest extends TestCase
{
    private const string FIXTURES_DIR = __DIR__ . '/../../Fixtures/StructureDefinitions';

    private const string AU_CORE_PROFILE_URL = 'http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient';

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

        $auBaseClass = new ClassType('AUBasePatientProfile', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\AuBase\\Profile'));
        $this->context->addType(
            'http://hl7.org.au/fhir/StructureDefinition/au-patient',
            'Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\AuBase\\Profile',
            $auBaseClass,
        );

        $this->namespace = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\AuCore\\Profile');
    }

    // -----------------------------------------------------------------
    // Cardinality constraints (Count) from min/max
    // -----------------------------------------------------------------

    public function testCountMinEmittedForDifferentialElementWithMinGreaterThanZero(): void
    {
        $sd    = $this->loadFixture('AUCorePatient.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $constraints = $this->getProfileConstraintAttributes($class);

        $nameConstraints = array_filter(
            $constraints,
            static fn (array $args) => ($args['path'] ?? '') === 'name'
                && ($args['constraint'] ?? '')               === Count::class,
        );

        self::assertNotEmpty($nameConstraints, 'Expected a Count constraint on "name" path');

        $nameConstraint = array_values($nameConstraints)[0];
        self::assertSame(['min' => 1], $nameConstraint['options'], 'Count min=1 for name (min:1, max:* → only min option)');
        self::assertSame([self::AU_CORE_PROFILE_URL], $nameConstraint['groups']);
    }

    public function testCountMinEmittedForIdentifierDifferentialElement(): void
    {
        $sd    = $this->loadFixture('AUCorePatient.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $constraints = $this->getProfileConstraintAttributes($class);

        $identifierConstraints = array_filter(
            $constraints,
            static fn (array $args) => ($args['path'] ?? '') === 'identifier'
                && ($args['constraint'] ?? '')               === Count::class,
        );

        self::assertNotEmpty($identifierConstraints, 'Expected a Count constraint on "identifier" path');

        $identifierConstraint = array_values($identifierConstraints)[0];
        self::assertSame(['min' => 1], $identifierConstraint['options']);
        self::assertSame([self::AU_CORE_PROFILE_URL], $identifierConstraint['groups']);
    }

    public function testRootDifferentialElementDoesNotProduceConstraint(): void
    {
        $sd    = $this->loadFixture('AUCorePatient.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $constraints = $this->getProfileConstraintAttributes($class);

        $rootConstraints = array_filter(
            $constraints,
            static fn (array $args) => ($args['path'] ?? '') === 'Patient' || ($args['path'] ?? '') === '',
        );

        self::assertEmpty($rootConstraints, 'Root differential element must not produce a profile constraint');
    }

    public function testNoConstraintEmittedWhenDifferentialAbsent(): void
    {
        $sd = $this->loadFixture('AUBasePatient.json'); // no differential block

        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $constraints = $this->getProfileConstraintAttributes($class);
        self::assertEmpty($constraints, 'No FHIRProfileConstraint expected when differential is absent');
    }

    public function testNoCountEmittedWhenMinIsZeroAndMaxIsStar(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/test-profile',
            'name'           => 'TestProfile',
            'type'           => 'Patient',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Patient',
            'differential'   => [
                'element' => [
                    ['id' => 'Patient.name', 'path' => 'Patient.name', 'min' => 0, 'max' => '*'],
                ],
            ],
        ];

        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertEmpty(
            $this->getProfileConstraintAttributes($class),
            'No Count constraint when min=0 and max=* (no cardinality tightening)',
        );
    }

    // -----------------------------------------------------------------
    // contentReference bail-out
    // -----------------------------------------------------------------

    public function testContentReferenceElementProducesNoConstraint(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/test-profile',
            'name'           => 'TestProfile',
            'type'           => 'Patient',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Patient',
            'differential'   => [
                'element' => [
                    [
                        'id'               => 'Patient.contact.name',
                        'path'             => 'Patient.contact.name',
                        'min'              => 1,
                        'contentReference' => '#Patient.name',
                    ],
                ],
            ],
        ];

        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertEmpty(
            $this->getProfileConstraintAttributes($class),
            'contentReference element must not produce a FHIRProfileConstraint',
        );
    }

    // -----------------------------------------------------------------
    // Fixed and pattern constraints
    // -----------------------------------------------------------------

    public function testFixedValueConstraintEmittedForFixedPolymorphicField(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/test-profile',
            'name'           => 'TestProfile',
            'type'           => 'Patient',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Patient',
            'differential'   => [
                'element' => [
                    [
                        'id'           => 'Patient.active',
                        'path'         => 'Patient.active',
                        'fixedBoolean' => true,
                    ],
                ],
            ],
        ];

        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $constraints      = $this->getProfileConstraintAttributes($class);
        $fixedConstraints = array_filter(
            $constraints,
            static fn (array $args) => ($args['path'] ?? '') === 'active'
                && ($args['constraint'] ?? '')               === FHIRFixedValue::class,
        );

        self::assertNotEmpty($fixedConstraints, 'Expected a FHIRFixedValue constraint on "active" path');
        $fixedConstraint = array_values($fixedConstraints)[0];
        self::assertSame(['value' => true], $fixedConstraint['options']);
    }

    public function testPatternValueConstraintEmittedForPatternPolymorphicField(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/test-profile',
            'name'           => 'TestProfile',
            'type'           => 'Patient',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Patient',
            'differential'   => [
                'element' => [
                    [
                        'id'             => 'Patient.identifier',
                        'path'           => 'Patient.identifier',
                        'patternCoding'  => ['system' => 'http://example.org/system'],
                    ],
                ],
            ],
        ];

        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $constraints        = $this->getProfileConstraintAttributes($class);
        $patternConstraints = array_filter(
            $constraints,
            static fn (array $args) => ($args['path'] ?? '') === 'identifier'
                && ($args['constraint'] ?? '')               === FHIRPatternValue::class,
        );

        self::assertNotEmpty($patternConstraints, 'Expected a FHIRPatternValue constraint on "identifier" path');
        $patternConstraint = array_values($patternConstraints)[0];
        self::assertSame(['pattern' => ['system' => 'http://example.org/system']], $patternConstraint['options']);
    }

    // -----------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------

    /**
     * Extract all FHIRProfileConstraint attribute argument arrays from the generated class.
     *
     * @return list<array<string, mixed>>
     */
    private function getProfileConstraintAttributes(ClassType $class): array
    {
        $results = [];
        foreach ($class->getAttributes() as $attribute) {
            if ($attribute->getName() === FHIRProfileConstraint::class) {
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
