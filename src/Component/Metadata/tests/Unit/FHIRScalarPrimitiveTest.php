<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRScalarPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\BooleanPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DecimalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\IntegerPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use PHPUnit\Framework\TestCase;

/**
 * Only boolean, integer and decimal wrappers are unwrapped: their properties hold a scalar unless the
 * element carries an extension (#207). Every other primitive property always holds its wrapper, and
 * callers rely on getting that wrapper back.
 */
final class FHIRScalarPrimitiveTest extends TestCase
{
    public function testBooleanIntegerAndDecimalWrappersAnswerTheirValue(): void
    {
        self::assertFalse(FHIRScalarPrimitive::unwrap(new BooleanPrimitive(value: false)));
        self::assertSame(3, FHIRScalarPrimitive::unwrap(new IntegerPrimitive(value: 3)));
        self::assertSame('1.50', FHIRScalarPrimitive::unwrap(new DecimalPrimitive(value: '1.50')));
    }

    public function testExtensionOnlyWrapperAnswersNull(): void
    {
        self::assertNull(FHIRScalarPrimitive::unwrap(new BooleanPrimitive()));
    }

    public function testScalarsAndOtherObjectsPassThrough(): void
    {
        $string = new StringPrimitive(value: 'a');

        self::assertTrue(FHIRScalarPrimitive::unwrap(true));
        self::assertNull(FHIRScalarPrimitive::unwrap(null));
        self::assertSame($string, FHIRScalarPrimitive::unwrap($string));
        self::assertFalse(FHIRScalarPrimitive::isScalarPrimitive($string));
    }
}
