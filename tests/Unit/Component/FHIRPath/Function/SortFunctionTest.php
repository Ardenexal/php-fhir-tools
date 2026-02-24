<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\SortFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for SortFunction.
 *
 * @covers \Ardenexal\FHIRTools\Component\FHIRPath\Function\SortFunction
 */
final class SortFunctionTest extends TestCase
{
    private SortFunction $function;

    private EvaluationContext $context;

    private FHIRPathEvaluator $evaluator;

    private FHIRPathLexer $lexer;

    private FHIRPathParser $parser;

    protected function setUp(): void
    {
        $this->function  = new SortFunction();
        $this->evaluator = new FHIRPathEvaluator(FunctionRegistry::getInstance());
        $this->context   = new EvaluationContext(evaluator: $this->evaluator);
        $this->lexer     = new FHIRPathLexer();
        $this->parser    = new FHIRPathParser();
    }

    /**
     * Parse a FHIRPath expression into an AST node.
     */
    private function parse(string $expression): ExpressionNode
    {
        $tokens = $this->lexer->tokenize($expression);

        return $this->parser->parse($tokens);
    }

    public function testFunctionName(): void
    {
        self::assertSame('sort', $this->function->getName());
    }

    public function testEmptyCollectionReturnsEmpty(): void
    {
        $input  = Collection::empty();
        $result = $this->function->execute($input, [], $this->context);

        self::assertTrue($result->isEmpty());
    }

    public function testSingleItemCollectionReturnsUnchanged(): void
    {
        $input  = Collection::single(42);
        $result = $this->function->execute($input, [], $this->context);

        self::assertSame(1, $result->count());
        self::assertSame(42, $result->first());
    }

    public function testNaturalSortAlreadySorted(): void
    {
        $input  = Collection::from([1, 2, 3]);
        $result = $this->function->execute($input, [], $this->context);

        self::assertSame([1, 2, 3], $result->toArray());
    }

    public function testNaturalSortReverseSorted(): void
    {
        $input  = Collection::from([3, 2, 1]);
        $result = $this->function->execute($input, [], $this->context);

        self::assertSame([1, 2, 3], $result->toArray());
    }

    public function testNaturalSortUnsorted(): void
    {
        $input  = Collection::from([2, 5, 1, 4, 3]);
        $result = $this->function->execute($input, [], $this->context);

        self::assertSame([1, 2, 3, 4, 5], $result->toArray());
    }

    public function testNaturalSortStrings(): void
    {
        $input  = Collection::from(['charlie', 'alpha', 'bravo']);
        $result = $this->function->execute($input, [], $this->context);

        self::assertSame(['alpha', 'bravo', 'charlie'], $result->toArray());
    }

    public function testNaturalSortFloats(): void
    {
        $input  = Collection::from([3.14, 2.71, 1.41, 1.73]);
        $result = $this->function->execute($input, [], $this->context);

        self::assertSame([1.41, 1.73, 2.71, 3.14], $result->toArray());
    }

    public function testNaturalSortMixedNumericTypes(): void
    {
        $input  = Collection::from([3, 1.5, 2, 0.5]);
        $result = $this->function->execute($input, [], $this->context);

        self::assertSame([0.5, 1.5, 2, 3], $result->toArray());
    }

    public function testNaturalSortThrowsOnMixedTypes(): void
    {
        $input = Collection::from([1, 'two', 3]);

        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessage('Cannot sort collection with mixed types');

        $this->function->execute($input, [], $this->context);
    }

    public function testExpressionSortAscending(): void
    {
        $input  = Collection::from([3, 1, 2]);
        $expr   = $this->parse('$this');
        $result = $this->function->execute($input, [$expr], $this->context);

        self::assertSame([1, 2, 3], $result->toArray());
    }

    public function testExpressionSortDescending(): void
    {
        $input  = Collection::from([1, 2, 3]);
        $expr   = $this->parse('-$this');
        $result = $this->function->execute($input, [$expr], $this->context);

        self::assertSame([3, 2, 1], $result->toArray());
    }

    public function testExpressionSortStringsAscending(): void
    {
        $input  = Collection::from(['c', 'a', 'b']);
        $expr   = $this->parse('$this');
        $result = $this->function->execute($input, [$expr], $this->context);

        self::assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testExpressionSortStringsDescending(): void
    {
        $input  = Collection::from(['a', 'b', 'c']);
        $expr   = $this->parse('-$this');
        $result = $this->function->execute($input, [$expr], $this->context);

        self::assertSame(['c', 'b', 'a'], $result->toArray());
    }

    public function testMultiKeySortWithObjects(): void
    {
        $input = Collection::from([
            ['family' => 'Smith', 'given' => 'John'],
            ['family' => 'Doe', 'given' => 'Jane'],
            ['family' => 'Smith', 'given' => 'Alice'],
        ]);

        $expr1  = $this->parse('family');
        $expr2  = $this->parse('given');
        $result = $this->function->execute($input, [$expr1, $expr2], $this->context);

        $expected = [
            ['family' => 'Doe', 'given' => 'Jane'],
            ['family' => 'Smith', 'given' => 'Alice'],
            ['family' => 'Smith', 'given' => 'John'],
        ];
        self::assertSame($expected, $result->toArray());
    }

    public function testMultiKeySortWithDescending(): void
    {
        $input = Collection::from([
            ['family' => 'Smith', 'given' => 'John'],
            ['family' => 'Doe', 'given' => 'Jane'],
            ['family' => 'Smith', 'given' => 'Alice'],
        ]);

        $expr1  = $this->parse('-family');
        $expr2  = $this->parse('-given');
        $result = $this->function->execute($input, [$expr1, $expr2], $this->context);

        $expected = [
            ['family' => 'Smith', 'given' => 'John'],
            ['family' => 'Smith', 'given' => 'Alice'],
            ['family' => 'Doe', 'given' => 'Jane'],
        ];
        self::assertSame($expected, $result->toArray());
    }

    public function testSortWithNullValues(): void
    {
        $input = Collection::from([
            ['name' => 'Charlie'],
            ['name' => null],
            ['name' => 'Alice'],
            ['name' => 'Bob'],
        ]);

        $expr   = $this->parse('name');
        $result = $this->function->execute($input, [$expr], $this->context);

        // Null should sort first
        self::assertNull($result->toArray()[0]['name']);
        self::assertSame('Alice', $result->toArray()[1]['name']);
        self::assertSame('Bob', $result->toArray()[2]['name']);
        self::assertSame('Charlie', $result->toArray()[3]['name']);
    }

    public function testSortWithEmptyResultFromExpression(): void
    {
        $input = Collection::from([
            ['values' => [3]],
            ['values' => []],
            ['values' => [1]],
            ['values' => [2]],
        ]);

        $expr   = $this->parse('values.first()');
        $result = $this->function->execute($input, [$expr], $this->context);

        // Empty result should be treated as null and sort first
        self::assertSame([], $result->toArray()[0]['values']);
        self::assertSame([1], $result->toArray()[1]['values']);
        self::assertSame([2], $result->toArray()[2]['values']);
        self::assertSame([3], $result->toArray()[3]['values']);
    }

    public function testSortThrowsOnMultipleValuesFromExpression(): void
    {
        $input = Collection::from([
            ['values' => [1, 2]],
            ['values' => [3, 4]],
        ]);

        $expr = $this->parse('values');

        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessage('Sort expression at position 0 returned multiple values');

        $this->function->execute($input, [$expr], $this->context);
    }

    public function testSortThrowsOnIncompatibleTypes(): void
    {
        $input = Collection::from([
            ['value' => 1],
            ['value' => 'two'],
        ]);

        $expr = $this->parse('value');

        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessage('Cannot compare incompatible types for sorting');

        $this->function->execute($input, [$expr], $this->context);
    }

    public function testSortBooleans(): void
    {
        $input  = Collection::from([true, false, true, false]);
        $result = $this->function->execute($input, [], $this->context);

        // false (0) sorts before true (1)
        self::assertSame([false, false, true, true], $result->toArray());
    }

    public function testSortWithNegativeNumbers(): void
    {
        $input  = Collection::from([5, -3, 0, -10, 2]);
        $result = $this->function->execute($input, [], $this->context);

        self::assertSame([-10, -3, 0, 2, 5], $result->toArray());
    }

    public function testRegisteredInFunctionRegistry(): void
    {
        $registry = FunctionRegistry::getInstance();
        $function = $registry->get('sort');

        self::assertInstanceOf(SortFunction::class, $function);
    }
}
