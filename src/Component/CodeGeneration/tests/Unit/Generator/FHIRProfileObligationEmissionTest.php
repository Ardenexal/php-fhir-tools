<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRProfileGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileObligation;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\TestCase;

/**
 * Verifies FHIRProfileGenerator emits #[FHIRProfileObligation] class-level attributes for
 * snapshot elements that carry obligation extensions, and that the attribute is readable via reflection.
 */
class FHIRProfileObligationEmissionTest extends TestCase
{
    private const string PROFILE_URL = 'http://example.org/fhir/StructureDefinition/test-profile';

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

        $this->namespace = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\Test\\Profile');
    }

    public function testObligationAttributeEmittedForSnapshotElementWithObligation(): void
    {
        $sd    = $this->buildSD([
            $this->obligationElement('Patient.identifier', 'SHALL:populate', 'http://example.org/actor/sender'),
        ]);
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $attrs = $this->getObligationAttributes($class);
        self::assertNotEmpty($attrs, '#[FHIRProfileObligation] must be emitted for snapshot element with obligation');

        $attr = $attrs[0];
        self::assertSame('identifier', $attr['path']);
        self::assertSame('SHALL:populate', $attr['code']);
        self::assertSame('http://example.org/actor/sender', $attr['actor']);
        self::assertSame([self::PROFILE_URL], $attr['groups']);
    }

    public function testMultipleObligationsOnOneElementAllEmitted(): void
    {
        $element = [
            'path'      => 'Patient.identifier',
            'extension' => [
                $this->obligationExt('SHALL:populate', 'http://example.org/actor/sender'),
                $this->obligationExt('SHOULD:display', null),
            ],
        ];

        $sd    = $this->buildSD([$element]);
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $attrs = $this->getObligationAttributes($class);
        self::assertCount(2, $attrs, 'Both obligation codes must produce a class-level attribute');

        $codes = array_column($attrs, 'code');
        self::assertContains('SHALL:populate', $codes);
        self::assertContains('SHOULD:display', $codes);
    }

    public function testObligationsOnMultipleElementsAllEmitted(): void
    {
        $sd    = $this->buildSD([
            $this->obligationElement('Patient.identifier', 'SHALL:populate', null),
            $this->obligationElement('Patient.name', 'SHOULD:handle', null),
        ]);
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $attrs = $this->getObligationAttributes($class);
        self::assertCount(2, $attrs);

        $paths = array_column($attrs, 'path');
        self::assertContains('identifier', $paths);
        self::assertContains('name', $paths);
    }

    public function testNoObligationAttributeWhenSnapshotHasNone(): void
    {
        $sd    = $this->buildSD([
            ['path' => 'Patient.identifier', 'extension' => []],
        ]);
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertEmpty(
            $this->getObligationAttributes($class),
            '#[FHIRProfileObligation] must NOT be emitted when no obligation extensions present',
        );
    }

    public function testRootElementDoesNotProduceObligationAttribute(): void
    {
        $sd = $this->buildSD([
            [
                'path'      => 'Patient', // root element — no dot
                'extension' => [
                    $this->obligationExt('SHALL:populate', null),
                ],
            ],
            $this->obligationElement('Patient.identifier', 'SHALL:populate', null),
        ]);

        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $attrs = $this->getObligationAttributes($class);

        self::assertCount(1, $attrs, 'Root element must not produce an obligation attribute');
        self::assertSame('identifier', $attrs[0]['path']);
    }

    public function testObligationAttributeIsReadableViaReflection(): void
    {
        $ref   = new \ReflectionClass(FHIRProfileObligationReflectionFixture::class);
        $attrs = $ref->getAttributes(FHIRProfileObligation::class);

        self::assertCount(2, $attrs, 'Both #[FHIRProfileObligation] attributes must be readable via ReflectionClass');

        $instances = array_map(static fn (\ReflectionAttribute $a) => $a->newInstance(), $attrs);
        $paths     = array_map(static fn (FHIRProfileObligation $o) => $o->path, $instances);
        self::assertContains('identifier', $paths);
        self::assertContains('code', $paths);

        foreach ($instances as $instance) {
            self::assertSame(['http://example.org/profile'], $instance->groups);
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Build a StructureDefinition with obligation extensions on snapshot elements.
     *
     * @param array<int, array<string, mixed>> $snapshotElements
     *
     * @return array<string, mixed>
     */
    private function buildSD(array $snapshotElements): array
    {
        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => self::PROFILE_URL,
            'name'           => 'TestPatientProfile',
            'type'           => 'Patient',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Patient',
            'snapshot'       => [
                'element' => array_merge(
                    [['path' => 'Patient', 'min' => 0, 'max' => '*']],
                    $snapshotElements,
                ),
            ],
        ];
    }

    /**
     * Build a snapshot element with a single obligation extension.
     *
     * @return array<string, mixed>
     */
    private function obligationElement(string $path, string $code, ?string $actor): array
    {
        return [
            'path'      => $path,
            'extension' => [
                $this->obligationExt($code, $actor),
            ],
        ];
    }

    /**
     * Build a single obligation extension sub-structure.
     *
     * @return array<string, mixed>
     */
    private function obligationExt(string $code, ?string $actor): array
    {
        $inner = [
            ['url' => 'code', 'valueCode' => $code],
        ];

        if ($actor !== null) {
            $inner[] = ['url' => 'actor', 'valueCanonical' => $actor];
        }

        return [
            'url'       => 'http://hl7.org/fhir/StructureDefinition/obligation',
            'extension' => $inner,
        ];
    }

    /**
     * @return list<array{path: string, code: string, groups: list<string>}>
     */
    private function getObligationAttributes(ClassType $class): array
    {
        $results = [];
        foreach ($class->getAttributes() as $attribute) {
            if ($attribute->getName() === FHIRProfileObligation::class) {
                /** @var array{path: string, code: string, groups: list<string>} $args */
                $args      = $attribute->getArguments();
                $results[] = $args;
            }
        }

        return $results;
    }
}

/**
 * Fixture class for reflection access test — simulates a generated profile class with two
 * #[FHIRProfileObligation] attributes as the generator would emit.
 */
#[FHIRProfileObligation(path: 'identifier', code: 'SHALL:populate', groups: ['http://example.org/profile'])]
#[FHIRProfileObligation(path: 'code', code: 'SHOULD:display', groups: ['http://example.org/profile'])]
final class FHIRProfileObligationReflectionFixture
{
}
