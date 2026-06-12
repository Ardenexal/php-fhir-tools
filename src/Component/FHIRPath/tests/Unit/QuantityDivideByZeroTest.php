<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PHPUnit\Framework\TestCase;

/**
 * B8 regression: dividing a quantity by a zero-valued quantity must yield an empty collection
 * (FHIRPath semantics), not an uncaught \DivisionByZeroError. The scalar and bc arithmetic paths
 * already guard zero divisors; the quantity DIVIDE branch did not.
 */
final class QuantityDivideByZeroTest extends TestCase
{
    private FHIRPathLexer $lexer;

    private FHIRPathParser $parser;

    private FHIRPathEvaluator $evaluator;

    protected function setUp(): void
    {
        $this->lexer     = new FHIRPathLexer();
        $this->parser    = new FHIRPathParser();
        $this->evaluator = new FHIRPathEvaluator();
    }

    protected function tearDown(): void
    {
        FunctionRegistry::reset();
    }

    private function evaluate(string $expression, mixed $resource = null): Collection
    {
        $tokens = $this->lexer->tokenize($expression);
        $ast    = $this->parser->parse($tokens);

        return $this->evaluator->evaluate($ast, $resource);
    }

    public function testQuantityDividedByZeroQuantityReturnsEmpty(): void
    {
        $result = $this->evaluate("1 'm' / 0 'm'");

        self::assertTrue($result->isEmpty());
    }
}
