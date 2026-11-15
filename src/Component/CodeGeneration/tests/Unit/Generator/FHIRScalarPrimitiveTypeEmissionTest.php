<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * A FHIR boolean, integer or decimal element is typed as its primitive wrapper or a native scalar,
 * so an element carrying an extension has somewhere to keep it (#207). The FHIRPath `System.*` codes
 * are a wrapper's own `value` and stay scalar-only.
 */
class FHIRScalarPrimitiveTypeEmissionTest extends TestCase
{
    private const string TEST_NS = 'Ardenexal\\FHIRTools\\ScalarPrimitiveTypeEmissionTest';

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function elementTypes(): iterable
    {
        yield 'boolean' => ['boolean', 'BooleanPrimitive|bool'];
        yield 'integer' => ['integer', 'IntegerPrimitive|int'];
        yield 'decimal' => ['decimal', 'DecimalPrimitive|string'];
        yield 'System.Boolean stays scalar' => ['http://hl7.org/fhirpath/System.Boolean', 'bool'];
        yield 'System.Decimal stays scalar' => ['http://hl7.org/fhirpath/System.Decimal', 'string'];
    }

    #[DataProvider('elementTypes')]
    public function testElementIsTypedAsWrapperOrScalar(string $code, string $expectedType): void
    {
        $context = new BuilderContext();
        $context->addElementNamespace('R4', new PhpNamespace(self::TEST_NS . '\\Resource'));
        $context->addDatatypeNamespace('R4', new PhpNamespace(self::TEST_NS . '\\DataType'));
        $context->addPrimitiveNamespace('R4', new PhpNamespace(self::TEST_NS . '\\Primitive'));
        $context->addEnumNamespace('R4', new PhpNamespace(self::TEST_NS . '\\Enum'));

        $class = (new FHIRModelGenerator())->generateModelClass(
            [
                'resourceType' => 'StructureDefinition',
                'url'          => 'http://hl7.org/fhir/StructureDefinition/ScalarType',
                'name'         => 'ScalarType',
                'kind'         => 'complex-type',
                'abstract'     => false,
                'snapshot'     => [
                    'element' => [
                        ['path' => 'ScalarType', 'min' => 0, 'max' => '1', 'base' => ['path' => 'ScalarType']],
                        [
                            'path' => 'ScalarType.flag',
                            'min'  => 0,
                            'max'  => '1',
                            'type' => [['code' => $code]],
                            'base' => ['path' => 'ScalarType.flag'],
                        ],
                    ],
                ],
            ],
            'R4',
            $context,
        );

        $parameter = $class->getMethod('__construct')->getParameter('flag');
        $type      = (string) $parameter->getType();
        // Strip the namespace from the wrapper so the assertion names only the short class.
        $shortType = str_replace(self::TEST_NS . '\\Primitive\\', '', ltrim($type, '\\'));

        self::assertSame($expectedType, str_replace(['|null', '?'], '', $shortType));
        self::assertTrue($parameter->isNullable() || str_contains($type, 'null'));
    }
}
