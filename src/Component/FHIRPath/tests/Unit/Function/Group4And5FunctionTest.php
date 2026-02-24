<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PHPUnit\Framework\TestCase;

/**
 * Tests for FHIRPath functions:
 *   - aggregate()        (Group 5 / spec §7)
 *   - toBoolean()        (Group 6 / spec §5.5)
 *   - convertsToBoolean()
 *   - toInteger()
 *
 * @author FHIR Tools Contributors
 */
final class Group4And5FunctionTest extends TestCase
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

    private function evaluate(string $expression, mixed $resource): Collection
    {
        $tokens = $this->lexer->tokenize($expression);
        $ast    = $this->parser->parse($tokens);

        return $this->evaluator->evaluate($ast, $resource);
    }

    // -------------------------------------------------------------------------
    // aggregate()
    // -------------------------------------------------------------------------

    public function testAggregateSum(): void
    {
        $resource = ['items' => [1, 2, 3, 4, 5]];
        $result   = $this->evaluate('items.aggregate($this + $total, 0)', $resource);

        self::assertTrue($result->isSingle());
        self::assertSame(15, $result->first());
    }

    public function testAggregateWithNoInitStartsEmpty(): void
    {
        // Without init, $total starts as null — first iteration result becomes $total
        // $this + $total where $total=null → null on first iteration is tricky;
        // use a string concat test that works with null gracefully
        $resource = ['items' => [1, 2, 3]];
        // With no init: first iter $total=null, $this=1 → null+1 is empty → $total stays null
        // subsequent items also get null+n → empty. Final result: empty.
        // Spec says $total starts as empty collection, not zero.
        $result = $this->evaluate('items.aggregate($this)', $resource);

        // With no init: $total=null; aggregator just returns $this each time → final $total = 3
        self::assertTrue($result->isSingle());
        self::assertSame(3, $result->first());
    }

    public function testAggregateOnEmptyCollectionReturnsInit(): void
    {
        $result = $this->evaluate('{}.aggregate($this + $total, 99)', null);

        // No items to iterate over → $total never changes from init
        self::assertTrue($result->isSingle());
        self::assertSame(99, $result->first());
    }

    public function testAggregateOnEmptyCollectionWithNoInitReturnsEmpty(): void
    {
        $result = $this->evaluate('{}.aggregate($this + $total)', null);

        self::assertTrue($result->isEmpty());
    }

    public function testAggregateCanBuildString(): void
    {
        $resource = ['parts' => ['a', 'b', 'c']];
        $result   = $this->evaluate("parts.aggregate(\$total & \$this, '')", $resource);

        self::assertTrue($result->isSingle());
        self::assertSame('abc', $result->first());
    }

    public function testAggregateCountItems(): void
    {
        $resource = ['items' => [10, 20, 30]];
        $result   = $this->evaluate('items.aggregate($total + 1, 0)', $resource);

        self::assertTrue($result->isSingle());
        self::assertSame(3, $result->first());
    }

    // -------------------------------------------------------------------------
    // toBoolean()
    // -------------------------------------------------------------------------

    public function testToBooleanPassthroughTrue(): void
    {
        self::assertTrue($this->evaluate('true.toBoolean()', null)->first());
    }

    public function testToBooleanPassthroughFalse(): void
    {
        self::assertFalse($this->evaluate('false.toBoolean()', null)->first());
    }

    public function testToBooleanFromStringTrue(): void
    {
        foreach (["'true'", "'1'", "'yes'", "'y'", "'on'"] as $expr) {
            $result = $this->evaluate("{$expr}.toBoolean()", null);
            self::assertTrue($result->isSingle(), "Expected single result for {$expr}");
            self::assertTrue($result->first(), "Expected true for {$expr}");
        }
    }

    public function testToBooleanFromStringFalse(): void
    {
        foreach (["'false'", "'0'", "'no'", "'n'", "'off'"] as $expr) {
            $result = $this->evaluate("{$expr}.toBoolean()", null);
            self::assertTrue($result->isSingle(), "Expected single result for {$expr}");
            self::assertFalse($result->first(), "Expected false for {$expr}");
        }
    }

    public function testToBooleanFromInteger(): void
    {
        self::assertTrue($this->evaluate('1.toBoolean()', null)->first());
        self::assertFalse($this->evaluate('0.toBoolean()', null)->first());
    }

    public function testToBooleanFromOtherIntegerReturnsEmpty(): void
    {
        self::assertTrue($this->evaluate('2.toBoolean()', null)->isEmpty());
    }

    public function testToBooleanFromUnrecognisedStringReturnsEmpty(): void
    {
        self::assertTrue($this->evaluate("'maybe'.toBoolean()", null)->isEmpty());
    }

    public function testToBooleanEmptyInputReturnsEmpty(): void
    {
        self::assertTrue($this->evaluate('{}.toBoolean()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // convertsToBoolean()
    // -------------------------------------------------------------------------

    public function testConvertsToBooleanTrueForConvertible(): void
    {
        self::assertTrue($this->evaluate("'true'.convertsToBoolean()", null)->first());
        self::assertTrue($this->evaluate('1.convertsToBoolean()', null)->first());
        self::assertTrue($this->evaluate('true.convertsToBoolean()', null)->first());
    }

    public function testConvertsToBooleanFalseForUnconvertible(): void
    {
        self::assertFalse($this->evaluate("'maybe'.convertsToBoolean()", null)->first());
        self::assertFalse($this->evaluate('2.convertsToBoolean()', null)->first());
    }

    public function testConvertsToBooleanEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.convertsToBoolean()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // toInteger()
    // -------------------------------------------------------------------------

    public function testToIntegerPassthrough(): void
    {
        $result = $this->evaluate('42.toInteger()', null);
        self::assertSame(42, $result->first());
    }

    public function testToIntegerFromBooleanTrue(): void
    {
        self::assertSame(1, $this->evaluate('true.toInteger()', null)->first());
    }

    public function testToIntegerFromBooleanFalse(): void
    {
        self::assertSame(0, $this->evaluate('false.toInteger()', null)->first());
    }

    public function testToIntegerFromNumericString(): void
    {
        self::assertSame(7, $this->evaluate("'7'.toInteger()", null)->first());
        self::assertSame(-3, $this->evaluate("'-3'.toInteger()", null)->first());
    }

    public function testToIntegerFromBooleanString(): void
    {
        self::assertSame(1, $this->evaluate("'true'.toInteger()", null)->first());
        self::assertSame(0, $this->evaluate("'false'.toInteger()", null)->first());
    }

    public function testToIntegerFromDecimalWithNoFraction(): void
    {
        $resource = ['val' => 3.0];
        self::assertSame(3, $this->evaluate('val.toInteger()', $resource)->first());
    }

    public function testToIntegerFromDecimalWithFractionReturnsEmpty(): void
    {
        $resource = ['val' => 3.5];
        self::assertTrue($this->evaluate('val.toInteger()', $resource)->isEmpty());
    }

    public function testToIntegerFromNonNumericStringReturnsEmpty(): void
    {
        self::assertTrue($this->evaluate("'hello'.toInteger()", null)->isEmpty());
    }

    public function testToIntegerEmptyInputReturnsEmpty(): void
    {
        self::assertTrue($this->evaluate('{}.toInteger()', null)->isEmpty());
    }
}
