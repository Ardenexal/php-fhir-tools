<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * lowBoundary() and highBoundary() on a PHP float that is not finite.
 *
 * A non-finite float renders as INF, -INF or NAN, which bcmath rejects with a PHP ValueError.
 * Both functions must report it as an EvaluationException instead.
 */
final class BoundaryNonFiniteTest extends TestCase
{
    protected function tearDown(): void
    {
        FunctionRegistry::reset();
    }

    /**
     * @return iterable<string, array{string, float}>
     */
    public static function nonFiniteProvider(): iterable
    {
        foreach (['lowBoundary', 'highBoundary'] as $function) {
            yield $function . ' INF' => [$function, INF];
            yield $function . ' -INF' => [$function, -INF];
            yield $function . ' NAN' => [$function, NAN];
        }
    }

    #[DataProvider('nonFiniteProvider')]
    public function testNonFiniteFloatThrowsEvaluationException(string $function, float $value): void
    {
        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessage('Cannot compute the boundary of non-finite value');

        $this->evaluate('value.' . $function . '()', ['value' => $value]);
    }

    public function testFiniteFloatStillHasBoundaries(): void
    {
        $low  = $this->evaluate('value.lowBoundary()', ['value' => 1.5]);
        $high = $this->evaluate('value.highBoundary()', ['value' => 1.5]);

        self::assertTrue($low->isSingle());
        self::assertTrue($high->isSingle());
    }

    private function evaluate(string $expression, mixed $resource): Collection
    {
        $tokens = (new FHIRPathLexer())->tokenize($expression);
        $ast    = (new FHIRPathParser())->parse($tokens);

        return (new FHIRPathEvaluator())->evaluate($ast, $resource);
    }
}
