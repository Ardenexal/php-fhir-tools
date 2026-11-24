<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Type;

use Ardenexal\FHIRTools\Component\FHIRPath\Type\DecimalNotation;
use PHPUnit\Framework\Attributes\CoversClass;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(DecimalNotation::class)]
final class DecimalNotationTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string}>
     */
    public static function numbers(): iterable
    {
        yield 'PHP float, large' => ['1.0E+25', '10000000000000000000000000'];
        yield 'PHP float, small' => ['1.5E-7', '0.00000015'];
        yield 'negative' => ['-2.5E+3', '-2500'];
        yield 'point lands inside the digits' => ['1.2345e2', '123.45'];
        yield 'no fraction' => ['3e-2', '0.03'];
        yield 'zero exponent keeps written fraction digits' => ['4.50e0', '4.50'];
        yield 'shift keeps significant trailing zero' => ['1.50e1', '15.0'];
        yield 'negative shift keeps significant trailing zero' => ['1.50e-1', '0.150'];
        yield 'negative zero with fraction' => ['-0.0e0', '0.0'];
        yield 'negative zero' => ['-0e5', '0'];
        yield 'plain stays as written' => ['1.50', '1.50'];
        yield 'not a number stays as written' => ['e5', 'e5'];
    }

    #[DataProvider('hugeExponents')]
    public function testHugeExponentThrowsEvaluationException(string $number): void
    {
        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessage('the exponent is beyond ±1000');

        DecimalNotation::plain($number);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function hugeExponents(): iterable
    {
        yield 'just past the limit' => ['1e1001'];
        yield 'negative, past the limit' => ['1e-1001'];
        yield 'past PHP_INT_MAX' => ['1E99999999999999999999'];
    }

    public function testExponentAtTheLimitIsRewritten(): void
    {
        self::assertSame('1' . str_repeat('0', 1000), DecimalNotation::plain('1e1000'));
    }

    #[DataProvider('numbers')]
    public function testPlain(string $number, string $plain): void
    {
        self::assertSame($plain, DecimalNotation::plain($number));
    }
}
