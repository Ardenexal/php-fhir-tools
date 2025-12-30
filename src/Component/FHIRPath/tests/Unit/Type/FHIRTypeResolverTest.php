<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Type;

use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRTypeResolver;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRDateTime;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRTypeResolver
 *
 * @author Ardenexal
 */
final class FHIRTypeResolverTest extends TestCase
{
    private FHIRTypeResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new FHIRTypeResolver();
    }

    public function testInferTypeFromPrimitiveValues(): void
    {
        self::assertSame('boolean', $this->resolver->inferType(true));
        self::assertSame('boolean', $this->resolver->inferType(false));
        self::assertSame('integer', $this->resolver->inferType(42));
        self::assertSame('decimal', $this->resolver->inferType(3.14));
        self::assertSame('string', $this->resolver->inferType('hello'));
        self::assertSame('undefined', $this->resolver->inferType(null));
    }

    public function testInferTypeFromFHIRPrimitives(): void
    {
        $fhirBoolean = new FHIRBoolean(value: true);
        self::assertSame('boolean', $this->resolver->inferType($fhirBoolean));

        $fhirString = new FHIRString(value: 'test');
        self::assertSame('string', $this->resolver->inferType($fhirString));

        $fhirInteger = new FHIRInteger(value: 123);
        self::assertSame('integer', $this->resolver->inferType($fhirInteger));

        $fhirDecimal = new FHIRDecimal(value: 99.99);
        self::assertSame('decimal', $this->resolver->inferType($fhirDecimal));

        $fhirDate = new FHIRDate(value: '2024-01-01');
        self::assertSame('date', $this->resolver->inferType($fhirDate));

        $fhirDateTime = new FHIRDateTime(value: new \DateTime('2024-01-01T10:00:00Z'));
        self::assertSame('dateTime', $this->resolver->inferType($fhirDateTime));
    }

    public function testIsOfTypeWithPrimitiveValues(): void
    {
        self::assertTrue($this->resolver->isOfType(true, 'boolean'));
        self::assertTrue($this->resolver->isOfType(42, 'integer'));
        self::assertTrue($this->resolver->isOfType(3.14, 'decimal'));
        self::assertTrue($this->resolver->isOfType('hello', 'string'));

        self::assertFalse($this->resolver->isOfType(true, 'string'));
        self::assertFalse($this->resolver->isOfType(42, 'boolean'));
    }

    public function testIsOfTypeWithFHIRPrimitives(): void
    {
        $fhirBoolean = new FHIRBoolean(value: true);
        self::assertTrue($this->resolver->isOfType($fhirBoolean, 'boolean'));
        self::assertTrue($this->resolver->isOfType($fhirBoolean, 'Boolean'));

        $fhirString = new FHIRString(value: 'test');
        self::assertTrue($this->resolver->isOfType($fhirString, 'string'));
        self::assertTrue($this->resolver->isOfType($fhirString, 'String'));

        $fhirInteger = new FHIRInteger(value: 123);
        self::assertTrue($this->resolver->isOfType($fhirInteger, 'integer'));
        self::assertTrue($this->resolver->isOfType($fhirInteger, 'Integer'));
    }

    public function testIsOfTypeWithAny(): void
    {
        self::assertTrue($this->resolver->isOfType(true, 'Any'));
        self::assertTrue($this->resolver->isOfType(42, 'Any'));
        self::assertTrue($this->resolver->isOfType('test', 'Any'));
        self::assertTrue($this->resolver->isOfType(new FHIRString(value: 'test'), 'Any'));
    }

    public function testIsOfTypeIntegerDecimalCompatibility(): void
    {
        // Integer should be compatible with decimal
        self::assertTrue($this->resolver->isOfType(42, 'decimal'));
        self::assertTrue($this->resolver->isOfType(new FHIRInteger(value: 42), 'decimal'));
    }

    public function testCastToBoolean(): void
    {
        self::assertTrue($this->resolver->castToType(true, 'boolean'));
        self::assertFalse($this->resolver->castToType(false, 'boolean'));
        self::assertTrue($this->resolver->castToType('true', 'boolean'));
        self::assertFalse($this->resolver->castToType('false', 'boolean'));
    }

    public function testCastToBooleanFromFHIRPrimitive(): void
    {
        // When value is already the correct FHIR type, return as-is
        $fhirBoolean = new FHIRBoolean(value: true);
        $result      = $this->resolver->castToType($fhirBoolean, 'boolean');
        self::assertInstanceOf(FHIRBoolean::class, $result);
        self::assertTrue($result->value);

        // When casting from FHIRString, extract the value
        $fhirString = new FHIRString(value: 'true');
        self::assertTrue($this->resolver->castToType($fhirString, 'boolean'));
    }

    public function testCastToString(): void
    {
        self::assertSame('hello', $this->resolver->castToType('hello', 'string'));
        self::assertSame('42', $this->resolver->castToType(42, 'string'));
        self::assertSame('3.14', $this->resolver->castToType(3.14, 'string'));
        self::assertSame('1', $this->resolver->castToType(true, 'string'));
    }

    public function testCastToInteger(): void
    {
        self::assertSame(42, $this->resolver->castToType(42, 'integer'));
        self::assertSame(3, $this->resolver->castToType(3.14, 'integer'));
        self::assertSame(100, $this->resolver->castToType('100', 'integer'));
    }

    public function testCastToDecimal(): void
    {
        self::assertSame(3.14, $this->resolver->castToType(3.14, 'decimal'));
        self::assertEqualsWithDelta(42.0, $this->resolver->castToType(42, 'decimal'), 0.001);
        self::assertSame(99.99, $this->resolver->castToType('99.99', 'decimal'));
    }

    public function testCastToTypeThrowsExceptionForInvalidCast(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->resolver->castToType('not-a-number', 'integer');
    }

    public function testCastToTypeThrowsExceptionForUnsupportedType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->resolver->castToType(42, 'UnsupportedType');
    }

    public function testIsPrimitiveType(): void
    {
        self::assertTrue($this->resolver->isPrimitiveType('boolean'));
        self::assertTrue($this->resolver->isPrimitiveType('string'));
        self::assertTrue($this->resolver->isPrimitiveType('integer'));
        self::assertTrue($this->resolver->isPrimitiveType('decimal'));
        self::assertTrue($this->resolver->isPrimitiveType('date'));
        self::assertTrue($this->resolver->isPrimitiveType('dateTime'));

        self::assertFalse($this->resolver->isPrimitiveType('Patient'));
        self::assertFalse($this->resolver->isPrimitiveType('Resource'));
    }

    public function testGetPhpType(): void
    {
        self::assertSame('boolean', $this->resolver->getPhpType('boolean'));
        self::assertSame('string', $this->resolver->getPhpType('string'));
        self::assertSame('integer', $this->resolver->getPhpType('integer'));
        self::assertSame('float', $this->resolver->getPhpType('decimal'));
        self::assertSame('string', $this->resolver->getPhpType('date'));
        self::assertSame('string', $this->resolver->getPhpType('dateTime'));

        self::assertNull($this->resolver->getPhpType('UnknownType'));
    }

    public function testCaseInsensitiveTypeChecking(): void
    {
        self::assertTrue($this->resolver->isOfType(42, 'Integer'));
        self::assertTrue($this->resolver->isOfType(42, 'INTEGER'));
        self::assertTrue($this->resolver->isOfType('test', 'String'));
        self::assertTrue($this->resolver->isOfType('test', 'STRING'));
    }
}
