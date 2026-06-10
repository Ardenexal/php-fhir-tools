<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Validation\CodingValidationResult;
use PHPUnit\Framework\TestCase;

final class CodingValidationResultTest extends TestCase
{
    public function testValidAndCorrectDisplayAreReadable(): void
    {
        $result = new CodingValidationResult(true, 'Australia');

        self::assertTrue($result->valid);
        self::assertSame('Australia', $result->correctDisplay);
    }

    public function testNullCorrectDisplayWhenDisplayNotChecked(): void
    {
        $result = new CodingValidationResult(false);

        self::assertFalse($result->valid);
        self::assertNull($result->correctDisplay);
    }

    public function testValidFalseWithNullCorrectDisplay(): void
    {
        $result = new CodingValidationResult(false, null);

        self::assertFalse($result->valid);
        self::assertNull($result->correctDisplay);
    }
}
