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
 * Tests for Quantity and String type-conversion functions:
 *   toQuantity(), convertsToQuantity(),
 *   toString(), convertsToString()
 *
 * @author FHIR Tools Contributors
 */
final class QuantityAndStringFunctionTest extends TestCase
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
    // toQuantity()
    // -------------------------------------------------------------------------

    public function testToQuantityFromInteger(): void
    {
        $resource = ['n' => 5];
        $result   = $this->evaluate('n.toQuantity()', $resource);
        self::assertFalse($result->isEmpty());
        $quantity = $result->first();
        self::assertIsArray($quantity);
        self::assertSame(5.0, $quantity['value']);
        self::assertSame('1', $quantity['unit']);
    }

    public function testToQuantityFromFloat(): void
    {
        $resource = ['n' => 3.14];
        $result   = $this->evaluate('n.toQuantity()', $resource);
        self::assertFalse($result->isEmpty());
        $quantity = $result->first();
        self::assertIsArray($quantity);
        self::assertSame(3.14, $quantity['value']);
        self::assertSame('1', $quantity['unit']);
    }

    public function testToQuantityFromBooleanTrue(): void
    {
        $result   = $this->evaluate('true.toQuantity()', null);
        $quantity = $result->first();
        self::assertIsArray($quantity);
        self::assertSame(1.0, $quantity['value']);
        self::assertSame('1', $quantity['unit']);
    }

    public function testToQuantityFromBooleanFalse(): void
    {
        $result   = $this->evaluate('false.toQuantity()', null);
        $quantity = $result->first();
        self::assertIsArray($quantity);
        self::assertSame(0.0, $quantity['value']);
        self::assertSame('1', $quantity['unit']);
    }

    public function testToQuantityFromPlainNumericString(): void
    {
        $resource = ['s' => '10'];
        $result   = $this->evaluate('s.toQuantity()', $resource);
        $quantity = $result->first();
        self::assertIsArray($quantity);
        self::assertSame(10.0, $quantity['value']);
        self::assertSame('1', $quantity['unit']);
    }

    public function testToQuantityFromQuantityLiteralString(): void
    {
        $resource = ['s' => "5.5 'mg'"];
        $result   = $this->evaluate('s.toQuantity()', $resource);
        $quantity = $result->first();
        self::assertIsArray($quantity);
        self::assertSame(5.5, $quantity['value']);
        self::assertSame('mg', $quantity['unit']);
    }

    public function testToQuantityFromQuantityArray(): void
    {
        $resource = ['q' => ['value' => 100.0, 'unit' => 'kg']];
        $result   = $this->evaluate('q.toQuantity()', $resource);
        $quantity = $result->first();
        self::assertIsArray($quantity);
        self::assertSame(100.0, $quantity['value']);
        self::assertSame('kg', $quantity['unit']);
    }

    public function testToQuantityWithMatchingUnitFilter(): void
    {
        $resource = ['s' => "10 'mg'"];
        $result   = $this->evaluate("s.toQuantity('mg')", $resource);
        $quantity = $result->first();
        self::assertIsArray($quantity);
        self::assertSame(10.0, $quantity['value']);
        self::assertSame('mg', $quantity['unit']);
    }

    public function testToQuantityWithMismatchedUnitFilterReturnsEmpty(): void
    {
        $resource = ['s' => "10 'mg'"];
        self::assertTrue($this->evaluate("s.toQuantity('kg')", $resource)->isEmpty());
    }

    public function testToQuantityFromInvalidStringReturnsEmpty(): void
    {
        self::assertTrue($this->evaluate("'not-a-number'.toQuantity()", null)->isEmpty());
    }

    public function testToQuantityFromComplexArrayReturnsEmpty(): void
    {
        // A non-Quantity array should not convert
        $resource = ['obj' => ['foo' => 'bar']];
        self::assertTrue($this->evaluate('obj.toQuantity()', $resource)->isEmpty());
    }

    public function testToQuantityEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.toQuantity()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // convertsToQuantity()
    // -------------------------------------------------------------------------

    public function testConvertsToQuantityTrueForInteger(): void
    {
        $resource = ['n' => 7];
        self::assertTrue($this->evaluate('n.convertsToQuantity()', $resource)->first());
    }

    public function testConvertsToQuantityTrueForQuantityString(): void
    {
        $resource = ['s' => "3.5 'L'"];
        self::assertTrue($this->evaluate('s.convertsToQuantity()', $resource)->first());
    }

    public function testConvertsToQuantityTrueWithMatchingUnit(): void
    {
        $resource = ['s' => "10 'mg'"];
        self::assertTrue($this->evaluate("s.convertsToQuantity('mg')", $resource)->first());
    }

    public function testConvertsToQuantityFalseWithMismatchedUnit(): void
    {
        $resource = ['s' => "10 'mg'"];
        self::assertFalse($this->evaluate("s.convertsToQuantity('kg')", $resource)->first());
    }

    public function testConvertsToQuantityFalseForInvalidString(): void
    {
        self::assertFalse($this->evaluate("'hello'.convertsToQuantity()", null)->first());
    }

    public function testConvertsToQuantityEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.convertsToQuantity()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // toString()
    // -------------------------------------------------------------------------

    public function testToStringPassthroughString(): void
    {
        $resource = ['s' => 'hello'];
        self::assertSame('hello', $this->evaluate('s.toString()', $resource)->first());
    }

    public function testToStringFromBooleanTrue(): void
    {
        self::assertSame('true', $this->evaluate('true.toString()', null)->first());
    }

    public function testToStringFromBooleanFalse(): void
    {
        self::assertSame('false', $this->evaluate('false.toString()', null)->first());
    }

    public function testToStringFromInteger(): void
    {
        self::assertSame('42', $this->evaluate('42.toString()', null)->first());
    }

    public function testToStringFromDecimalWithFraction(): void
    {
        $resource = ['n' => 3.14];
        $result   = $this->evaluate('n.toString()', $resource)->first();
        self::assertSame('3.14', $result);
    }

    public function testToStringFromWholeDecimalIncludesDecimalPoint(): void
    {
        $resource = ['n' => 42.0];
        $result   = $this->evaluate('n.toString()', $resource)->first();
        // Float 42.0 must include decimal separator per spec
        self::assertStringContainsString('.', $result);
        self::assertStringStartsWith('42', $result);
    }

    public function testToStringFromDateString(): void
    {
        // Date strings are already strings — pass-through
        $resource = ['d' => '2020-06-15'];
        self::assertSame('2020-06-15', $this->evaluate('d.toString()', $resource)->first());
    }

    public function testToStringFromQuantityArray(): void
    {
        $resource = ['q' => ['value' => 10.0, 'unit' => 'mg']];
        $result   = $this->evaluate('q.toString()', $resource)->first();
        self::assertSame("10.0 'mg'", $result);
    }

    public function testToStringFromQuantityArrayWithFractionalValue(): void
    {
        $resource = ['q' => ['value' => 5.5, 'unit' => 'kg']];
        $result   = $this->evaluate('q.toString()', $resource)->first();
        self::assertSame("5.5 'kg'", $result);
    }

    public function testToStringFromNonQuantityComplexArrayReturnsEmpty(): void
    {
        $resource = ['obj' => ['foo' => 'bar']];
        self::assertTrue($this->evaluate('obj.toString()', $resource)->isEmpty());
    }

    public function testToStringEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.toString()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // convertsToString()
    // -------------------------------------------------------------------------

    public function testConvertsToStringTrueForString(): void
    {
        $resource = ['s' => 'hello'];
        self::assertTrue($this->evaluate('s.convertsToString()', $resource)->first());
    }

    public function testConvertsToStringTrueForBoolean(): void
    {
        self::assertTrue($this->evaluate('true.convertsToString()', null)->first());
    }

    public function testConvertsToStringTrueForInteger(): void
    {
        self::assertTrue($this->evaluate('42.convertsToString()', null)->first());
    }

    public function testConvertsToStringTrueForQuantity(): void
    {
        $resource = ['q' => ['value' => 5.0, 'unit' => 'mg']];
        self::assertTrue($this->evaluate('q.convertsToString()', $resource)->first());
    }

    public function testConvertsToStringFalseForComplexArray(): void
    {
        $resource = ['obj' => ['foo' => 'bar']];
        self::assertFalse($this->evaluate('obj.convertsToString()', $resource)->first());
    }

    public function testConvertsToStringEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.convertsToString()', null)->isEmpty());
    }
}
