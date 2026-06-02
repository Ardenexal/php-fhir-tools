<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRMustSupport;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Count;

/**
 * Verifies that FHIRModelGenerator emits #[FHIRMustSupport] on properties whose StructureDefinition
 * element declares mustSupport=true, and does not emit it otherwise.
 */
class FHIRMustSupportEmissionTest extends TestCase
{
    private const string TEST_NS = 'Ardenexal\\FHIRTools\\MustSupportEmissionTest';

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

    public function testMustSupportEmittedWhenTrue(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('MSRequiredType', 'complex-type', [
                [
                    'path'        => 'MSRequiredType.name',
                    'min'         => 0,
                    'max'         => '*',
                    'mustSupport' => true,
                    'type'        => [['code' => 'http://hl7.org/fhirpath/System.String']],
                    'base'        => ['path' => 'MSRequiredType.name'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('name')->getAttributes(FHIRMustSupport::class);
        self::assertNotEmpty($attrs, '#[FHIRMustSupport] must be emitted when mustSupport=true');
    }

    public function testMustSupportNotEmittedWhenAbsent(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('MSAbsentType', 'complex-type', [
                [
                    'path' => 'MSAbsentType.name',
                    'min'  => 0,
                    'max'  => '*',
                    'type' => [['code' => 'http://hl7.org/fhirpath/System.String']],
                    'base' => ['path' => 'MSAbsentType.name'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('name')->getAttributes(FHIRMustSupport::class);
        self::assertEmpty($attrs, '#[FHIRMustSupport] must NOT be emitted when mustSupport key is absent');
    }

    public function testMustSupportNotEmittedWhenFalse(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('MSFalseType', 'complex-type', [
                [
                    'path'        => 'MSFalseType.name',
                    'min'         => 0,
                    'max'         => '*',
                    'mustSupport' => false,
                    'type'        => [['code' => 'http://hl7.org/fhirpath/System.String']],
                    'base'        => ['path' => 'MSFalseType.name'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('name')->getAttributes(FHIRMustSupport::class);
        self::assertEmpty($attrs, '#[FHIRMustSupport] must NOT be emitted when mustSupport=false');
    }

    public function testMustSupportAndCountConstraintCoexist(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('MSWithCountType', 'complex-type', [
                [
                    'path'        => 'MSWithCountType.identifier',
                    'min'         => 1,
                    'max'         => '*',
                    'mustSupport' => true,
                    'type'        => [['code' => 'http://hl7.org/fhirpath/System.String']],
                    'base'        => ['path' => 'MSWithCountType.identifier'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn    = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $propRef = (new \ReflectionClass($fqcn))->getProperty('identifier');
        self::assertNotEmpty($propRef->getAttributes(FHIRMustSupport::class), '#[FHIRMustSupport] must be emitted');
        self::assertNotEmpty($propRef->getAttributes(Count::class), '#[Count] must also be emitted alongside #[FHIRMustSupport]');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * @param array<int, array<string, mixed>> $propertyElements
     *
     * @return array<string, mixed>
     */
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
