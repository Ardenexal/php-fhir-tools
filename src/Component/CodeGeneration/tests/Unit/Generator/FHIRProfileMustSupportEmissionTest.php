<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\CodeGeneration\tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRProfileGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that FHIRProfileGenerator emits #[FHIRProfileMustSupport] class-level attributes for
 * differential elements declaring mustSupport=true, and that the attribute is readable via reflection.
 */
class FHIRProfileMustSupportEmissionTest extends TestCase
{
    private const string PROFILE_URL = 'http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient';

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

        $this->namespace = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\AuCore\\Profile');
    }

    public function testMustSupportAttributeEmittedForDifferentialElementWithMustSupportTrue(): void
    {
        $sd    = $this->buildSD([
            ['id' => 'Patient.name', 'path' => 'Patient.name', 'mustSupport' => true],
        ]);
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $attrs = $this->getMustSupportAttributes($class);
        self::assertNotEmpty($attrs, '#[FHIRProfileMustSupport] must be emitted for mustSupport=true element');

        $nameAttrs = array_filter($attrs, static fn (array $a) => $a['path'] === 'name');
        self::assertNotEmpty($nameAttrs, 'Expected path "name" in emitted #[FHIRProfileMustSupport]');
        $attr = array_values($nameAttrs)[0];
        self::assertSame([self::PROFILE_URL], $attr['groups']);
    }

    public function testMultipleMustSupportElementsAllEmitted(): void
    {
        $sd    = $this->buildSD([
            ['id' => 'Patient.name', 'path' => 'Patient.name', 'mustSupport' => true],
            ['id' => 'Patient.identifier', 'path' => 'Patient.identifier', 'mustSupport' => true],
        ]);
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $attrs = $this->getMustSupportAttributes($class);
        self::assertCount(2, $attrs, 'Both must-support elements must produce a class-level attribute');

        $paths = array_column($attrs, 'path');
        self::assertContains('name', $paths);
        self::assertContains('identifier', $paths);
    }

    public function testNoMustSupportAttributeWhenFlagAbsent(): void
    {
        $sd    = $this->buildSD([
            ['id' => 'Patient.name', 'path' => 'Patient.name', 'min' => 1],
        ]);
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertEmpty(
            $this->getMustSupportAttributes($class),
            '#[FHIRProfileMustSupport] must NOT be emitted when mustSupport key is absent',
        );
    }

    public function testNoMustSupportAttributeWhenFlagFalse(): void
    {
        $sd    = $this->buildSD([
            ['id' => 'Patient.name', 'path' => 'Patient.name', 'mustSupport' => false],
        ]);
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertEmpty(
            $this->getMustSupportAttributes($class),
            '#[FHIRProfileMustSupport] must NOT be emitted when mustSupport=false',
        );
    }

    public function testRootElementDoesNotProduceMustSupportAttribute(): void
    {
        $sd    = $this->buildSD([
            ['id' => 'Patient', 'path' => 'Patient', 'mustSupport' => true],
            ['id' => 'Patient.name', 'path' => 'Patient.name', 'mustSupport' => true],
        ]);
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $attrs = $this->getMustSupportAttributes($class);
        self::assertCount(1, $attrs, 'Root element must not produce a must-support attribute');
        self::assertSame('name', $attrs[0]['path']);
    }

    public function testNoDifferentialProducesNoMustSupportAttributes(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => self::PROFILE_URL,
            'name'           => 'AUCorePatientProfile',
            'type'           => 'Patient',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Patient',
        ];

        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertEmpty($this->getMustSupportAttributes($class), 'No must-support attributes expected when differential is absent');
    }

    /**
     * Verify that the PHP attribute is readable via reflection — this is the primary consumption
     * pattern for tooling that discovers must-support properties at runtime.
     */
    public function testFHIRProfileMustSupportAttributeIsReadableViaReflection(): void
    {
        $ref   = new \ReflectionClass(FHIRProfileMustSupportReflectionFixture::class);
        $attrs = $ref->getAttributes(FHIRProfileMustSupport::class);

        self::assertCount(2, $attrs, 'Both #[FHIRProfileMustSupport] attributes must be readable via ReflectionClass');

        $instances = array_map(static fn (\ReflectionAttribute $a) => $a->newInstance(), $attrs);
        $paths     = array_map(static fn (FHIRProfileMustSupport $ms) => $ms->path, $instances);
        self::assertContains('name', $paths);
        self::assertContains('identifier', $paths);

        foreach ($instances as $instance) {
            self::assertSame(['http://example.org/profile'], $instance->groups);
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * @param array<int, array<string, mixed>> $differentialElements
     *
     * @return array<string, mixed>
     */
    private function buildSD(array $differentialElements): array
    {
        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => self::PROFILE_URL,
            'name'           => 'AUCorePatientProfile',
            'type'           => 'Patient',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Patient',
            'differential'   => ['element' => $differentialElements],
        ];
    }

    /**
     * @return list<array{path: string, groups: list<string>}>
     */
    private function getMustSupportAttributes(ClassType $class): array
    {
        $results = [];
        foreach ($class->getAttributes() as $attribute) {
            if ($attribute->getName() === FHIRProfileMustSupport::class) {
                /** @var array{path: string, groups: list<string>} $args */
                $args      = $attribute->getArguments();
                $results[] = $args;
            }
        }

        return $results;
    }
}

/**
 * Fixture class for reflection access test — simulates a generated profile class with two
 * #[FHIRProfileMustSupport] attributes as the generator would emit.
 */
#[FHIRProfileMustSupport(path: 'name', groups: ['http://example.org/profile'])]
#[FHIRProfileMustSupport(path: 'identifier', groups: ['http://example.org/profile'])]
final class FHIRProfileMustSupportReflectionFixture
{
}
