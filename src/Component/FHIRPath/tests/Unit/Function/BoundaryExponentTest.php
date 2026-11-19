<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\HighBoundaryFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\LowBoundaryFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDecimal;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * lowBoundary() and highBoundary() on a decimal written with an exponent.
 *
 * PHP prints a large or small float with one (`1.0E+25`), and bcmath rejected that form with a
 * ValueError that ended validation. The boundary is now computed from the plain digits.
 */
#[CoversClass(LowBoundaryFunction::class)]
#[CoversClass(HighBoundaryFunction::class)]
final class BoundaryExponentTest extends TestCase
{
    protected function tearDown(): void
    {
        FunctionRegistry::reset();
    }

    /**
     * @return iterable<string, array{string, float|FHIRPathDecimal, string}>
     */
    public static function boundaries(): iterable
    {
        yield 'low of a large float' => ['lowBoundary', 1e25, '9999999999999999999999999.50000000'];
        yield 'high of a large float' => ['highBoundary', 1e25, '10000000000000000000000000.50000000'];
        yield 'low of a large negative float' => ['lowBoundary', -2e30, '-2000000000000000000000000000000.50000000'];
        yield 'low of a small float' => ['lowBoundary', 1.5e-7, '0.00000014'];
        yield 'high of a small float' => ['highBoundary', 1.5e-7, '0.00000016'];
        yield 'low of a decimal with an exponent' => ['lowBoundary', new FHIRPathDecimal('1.25e2'), '124.50000000'];
        yield 'high of a decimal with an exponent' => ['highBoundary', new FHIRPathDecimal('1.25e2'), '125.50000000'];
    }

    #[DataProvider('boundaries')]
    public function testBoundaryOfAValueWithAnExponent(string $function, float|FHIRPathDecimal $value, string $expected): void
    {
        $result = $this->evaluate('value.' . $function . '()', ['value' => $value])->first();

        self::assertInstanceOf(FHIRPathDecimal::class, $result);
        self::assertSame($expected, $result->value);
    }

    private function evaluate(string $expression, mixed $resource): Collection
    {
        $tokens = (new FHIRPathLexer())->tokenize($expression);
        $ast    = (new FHIRPathParser())->parse($tokens);

        return (new FHIRPathEvaluator())->evaluate($ast, $resource);
    }
}
