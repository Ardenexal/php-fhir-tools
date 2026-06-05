<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Ucum;

use Ardenexal\FHIRTools\Component\Metadata\Ucum\UcumConverter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @author Ardenexal
 */
class UcumConverterTest extends TestCase
{
    private UcumConverter $ucum;

    protected function setUp(): void
    {
        $this->ucum = new UcumConverter();
    }

    // ---------------------------------------------------------------------------
    // toBase
    // ---------------------------------------------------------------------------

    public function testToBaseConvertsAKnownCodeToItsCanonicalUnit(): void
    {
        $result = $this->ucum->toBase('km', 1.5);

        $this->assertSame('m', $result['base']);
        $this->assertSame(1500.0, $result['value']);
    }

    public function testToBaseReturnsNullForAnUnknownCode(): void
    {
        $this->assertNull($this->ucum->toBase('Cel', 37.0));
    }

    // ---------------------------------------------------------------------------
    // knows
    // ---------------------------------------------------------------------------

    public function testKnowsReportsTableMembership(): void
    {
        $this->assertTrue($this->ucum->knows('kg'));
        $this->assertTrue($this->ucum->knows('[mi_i]'));
        // A valid UCUM unit that is simply absent from the minimal table.
        $this->assertFalse($this->ucum->knows('Cel'));
        $this->assertFalse($this->ucum->knows(''));
    }

    // ---------------------------------------------------------------------------
    // areCommensurable
    // ---------------------------------------------------------------------------

    public function testCommensurableWhenCodesShareABase(): void
    {
        $this->assertTrue($this->ucum->areCommensurable('m', 'km'));
    }

    public function testNotCommensurableAcrossDimensionsOrForUnknownCodes(): void
    {
        $this->assertFalse($this->ucum->areCommensurable('kg', '[mi_i]'));
        $this->assertFalse($this->ucum->areCommensurable('m', 'Cel'));
    }

    // ---------------------------------------------------------------------------
    // compare
    // ---------------------------------------------------------------------------

    /**
     * @param -1|0|1 $expected
     */
    #[DataProvider('comparablePairs')]
    public function testCompareOrdersCommensurableQuantities(float $valueA, string $codeA, float $valueB, string $codeB, int $expected): void
    {
        $this->assertSame($expected, $this->ucum->compare($valueA, $codeA, $valueB, $codeB));
    }

    /**
     * @return iterable<string, array{float, string, float, string, -1|0|1}>
     */
    public static function comparablePairs(): iterable
    {
        yield '1 m < 1 km'            => [1.0, 'm', 1.0, 'km', -1];
        yield '1000 m == 1 km'       => [1000.0, 'm', 1.0, 'km', 0];
        yield '2 km > 1500 m'        => [2.0, 'km', 1500.0, 'm', 1];
        yield '1 kg == 1000 g'       => [1.0, 'kg', 1000.0, 'g', 0];
    }

    public function testCompareReturnsNullForIncompatibleDimensions(): void
    {
        // Both codes are known, but kg (mass) and miles (length) cannot be ordered.
        $this->assertNull($this->ucum->compare(1.0, 'kg', 1.0, '[mi_i]'));
    }

    public function testCompareReturnsNullWhenACodeIsUnknown(): void
    {
        // Distinct from a dimensional mismatch: the unit is valid UCUM but outside the table.
        $this->assertNull($this->ucum->compare(37.0, 'Cel', 1.0, 'kg'));
        $this->assertFalse($this->ucum->knows('Cel'));
    }
}
