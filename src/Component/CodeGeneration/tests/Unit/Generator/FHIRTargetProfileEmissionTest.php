<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that the generator emits #[FHIRTargetProfile] when element.type[].targetProfile is present.
 */
final class FHIRTargetProfileEmissionTest extends TestCase
{
    private const string TEST_NS = 'Ardenexal\\FHIRTools\\TargetProfileEmissionTest';

    private const string PROFILE_A = 'http://hl7.org/fhir/StructureDefinition/au-core-patient';

    private const string PROFILE_B = 'http://hl7.org/fhir/StructureDefinition/au-base-patient';

    private FHIRModelGenerator $generator;

    private BuilderContext $context;

    protected function setUp(): void
    {
        $this->generator = new FHIRModelGenerator();
        $this->context   = new BuilderContext();
        $this->context->addElementNamespace('R4', new PhpNamespace(self::TEST_NS . '\\Resource'));
        $this->context->addDatatypeNamespace('R4', new PhpNamespace(self::TEST_NS . '\\DataType'));
        $this->context->addPrimitiveNamespace('R4', new PhpNamespace(self::TEST_NS . '\\Primitive'));
        $this->context->addEnumNamespace('R4', new PhpNamespace(self::TEST_NS . '\\Enum'));
    }

    public function testTargetProfileEmittedForReferenceTypedElement(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('RefType1', 'complex-type', [
                [
                    'path' => 'RefType1.subject',
                    'min'  => 0,
                    'max'  => '1',
                    'type' => [['code' => 'Reference', 'targetProfile' => [self::PROFILE_A]]],
                    'base' => ['path' => 'RefType1.subject'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('subject')->getAttributes(FHIRTargetProfile::class);
        self::assertNotEmpty($attrs, '#[FHIRTargetProfile] must be emitted when targetProfile is present');

        $instance = $attrs[0]->newInstance();
        self::assertSame([self::PROFILE_A], $instance->targetProfiles);
    }

    public function testMultipleTargetProfilesCollectedIntoSingleAttribute(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('RefType2', 'complex-type', [
                [
                    'path' => 'RefType2.performer',
                    'min'  => 0,
                    'max'  => '1',
                    'type' => [['code' => 'Reference', 'targetProfile' => [self::PROFILE_A, self::PROFILE_B]]],
                    'base' => ['path' => 'RefType2.performer'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn     = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs    = (new \ReflectionClass($fqcn))->getProperty('performer')->getAttributes(FHIRTargetProfile::class);
        $instance = $attrs[0]->newInstance();
        self::assertCount(2, $instance->targetProfiles);
        self::assertContains(self::PROFILE_A, $instance->targetProfiles);
        self::assertContains(self::PROFILE_B, $instance->targetProfiles);
    }

    public function testTargetProfilesFromMultipleTypeEntriesMergedAndDeduplicated(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('RefType3', 'complex-type', [
                [
                    'path' => 'RefType3.author',
                    'min'  => 0,
                    'max'  => '1',
                    'type' => [
                        ['code' => 'Reference', 'targetProfile' => [self::PROFILE_A, self::PROFILE_B]],
                        ['code' => 'Reference', 'targetProfile' => [self::PROFILE_B]],
                    ],
                    'base' => ['path' => 'RefType3.author'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn     = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs    = (new \ReflectionClass($fqcn))->getProperty('author')->getAttributes(FHIRTargetProfile::class);
        $instance = $attrs[0]->newInstance();
        self::assertCount(2, $instance->targetProfiles, 'Duplicate profile URLs must be deduplicated');
    }

    public function testNoTargetProfileAttributeWhenTargetProfileAbsent(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('RefType4', 'complex-type', [
                [
                    'path' => 'RefType4.subject',
                    'min'  => 0,
                    'max'  => '1',
                    'type' => [['code' => 'Reference']],
                    'base' => ['path' => 'RefType4.subject'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('subject')->getAttributes(FHIRTargetProfile::class);
        self::assertEmpty($attrs, '#[FHIRTargetProfile] must NOT be emitted when targetProfile is absent');
    }

    public function testNoTargetProfileAttributeWhenTargetProfileIsEmptyArray(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('RefType5', 'complex-type', [
                [
                    'path' => 'RefType5.subject',
                    'min'  => 0,
                    'max'  => '1',
                    'type' => [['code' => 'Reference', 'targetProfile' => []]],
                    'base' => ['path' => 'RefType5.subject'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('subject')->getAttributes(FHIRTargetProfile::class);
        self::assertEmpty($attrs, '#[FHIRTargetProfile] must NOT be emitted when targetProfile array is empty');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /** @param array<int, array<string, mixed>> $propertyElements */
    private function buildSD(string $name, string $kind, array $propertyElements): array
    {
        return [
            'resourceType' => 'StructureDefinition',
            'url'          => 'http://hl7.org/fhir/StructureDefinition/' . $name,
            'name'         => $name,
            'kind'         => $kind,
            'abstract'     => false,
            'snapshot'     => [
                'element' => array_merge(
                    [['path' => $name, 'min' => 0, 'max' => '1', 'base' => ['path' => $name]]],
                    $propertyElements,
                ),
            ],
        ];
    }

    private function evalClass(ClassType $class, string $namespace): string
    {
        $printer = new Printer();
        $nsObj   = new PhpNamespace($namespace);
        $code    = "<?php declare(strict_types=1);\n\nnamespace {$namespace};\n\n"
            . $printer->printClass($class, $nsObj);
        eval(substr($code, 5));

        return $namespace . '\\' . $class->getName();
    }
}
