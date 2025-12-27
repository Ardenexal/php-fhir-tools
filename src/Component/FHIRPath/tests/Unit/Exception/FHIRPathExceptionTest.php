<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Exception;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the base FHIRPathException class.
 *
 * @author FHIR Tools Contributors
 */
class FHIRPathExceptionTest extends TestCase
{
    public function testConstructorSetsProperties(): void
    {
        $exception = new FHIRPathException(
            'Test error',
            10,
            5,
            'test.expression',
            'Try this instead',
        );

        self::assertEquals('Test error', $exception->getMessage());
        self::assertEquals(10, $exception->getExpressionLine());
        self::assertEquals(5, $exception->getExpressionColumn());
        self::assertEquals('test.expression', $exception->getExpressionContext());
        self::assertEquals('Try this instead', $exception->getSuggestion());
    }

    public function testGetFullMessageIncludesAllDetails(): void
    {
        $exception = new FHIRPathException(
            'Test error',
            10,
            5,
            'name.where(invalid)',
            'Check the syntax',
        );

        $fullMessage = $exception->getFullMessage();

        self::assertStringContainsString('Test error', $fullMessage);
        self::assertStringContainsString('line 10', $fullMessage);
        self::assertStringContainsString('column 5', $fullMessage);
        self::assertStringContainsString('name.where(invalid)', $fullMessage);
        self::assertStringContainsString('Check the syntax', $fullMessage);
    }

    public function testGetPositionReturnsArray(): void
    {
        $exception = new FHIRPathException('Test error', 10, 5);

        $position = $exception->getPosition();

        self::assertIsArray($position);
        self::assertEquals(10, $position['line']);
        self::assertEquals(5, $position['column']);
    }

    public function testExceptionWithoutOptionalParameters(): void
    {
        $exception = new FHIRPathException('Simple error');

        self::assertEquals('Simple error', $exception->getMessage());
        self::assertEquals(0, $exception->getExpressionLine());
        self::assertEquals(0, $exception->getExpressionColumn());
        self::assertEquals('', $exception->getExpressionContext());
        self::assertNull($exception->getSuggestion());
    }
}
