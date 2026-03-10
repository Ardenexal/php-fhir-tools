<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Evaluator;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRDecimal;
use PHPUnit\Framework\TestCase;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * Tests for type expression evaluation (is/as operators) with FHIR models.
 *
 * @covers \Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator::visitTypeExpression
 *
 * @author Ardenexal
 */
final class TypeExpressionEvaluatorTest extends TestCase
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

    private function evaluate(string $expression, mixed $resource): Collection
    {
        $tokens = $this->lexer->tokenize($expression);
        $ast    = $this->parser->parse($tokens);

        return $this->evaluator->evaluate($ast, $resource);
    }

    public function testIsOperatorWithPrimitiveValues(): void
    {
        // FHIRPath spec: x is T returns a single boolean, not the item itself.

        // Integer is integer → true
        $result = $this->evaluate('42 is integer', null);
        self::assertSame(1, $result->count());
        self::assertTrue($result->first());

        // String is string → true
        $result = $this->evaluate("'hello' is string", null);
        self::assertSame(1, $result->count());
        self::assertTrue($result->first());

        // Boolean is boolean → true
        $result = $this->evaluate('true is boolean', null);
        self::assertSame(1, $result->count());
        self::assertTrue($result->first());
    }

    public function testIsOperatorWithFHIRPrimitives(): void
    {
        // FHIRPath spec: x is T returns a boolean result, not the item itself.

        // FHIRString is string → true
        $fhirString = new FHIRString(value: 'test');
        $result     = $this->evaluate('$this is string', $fhirString);
        self::assertSame(1, $result->count());
        self::assertTrue($result->first());

        // FHIRInteger is integer → true
        $fhirInteger = new FHIRInteger(value: 123);
        $result      = $this->evaluate('$this is integer', $fhirInteger);
        self::assertSame(1, $result->count());
        self::assertTrue($result->first());

        // FHIRBoolean is boolean → true
        $fhirBoolean = new FHIRBoolean(value: true);
        $result      = $this->evaluate('$this is boolean', $fhirBoolean);
        self::assertSame(1, $result->count());
        self::assertTrue($result->first());
    }

    public function testIsOperatorWithTypeNegativeMatch(): void
    {
        // FHIRPath spec: x is T returns false (not empty) when types don't match.

        // Integer is not string → false
        $result = $this->evaluate('42 is string', null);
        self::assertSame(1, $result->count());
        self::assertFalse($result->first());

        // String is not integer → false
        $result = $this->evaluate("'hello' is integer", null);
        self::assertSame(1, $result->count());
        self::assertFalse($result->first());
    }

    public function testIsOperatorWithAnyType(): void
    {
        // Any type matches everything
        $result = $this->evaluate('42 is Any', null);
        self::assertSame(1, $result->count());

        $result = $this->evaluate("'hello' is Any", null);
        self::assertSame(1, $result->count());

        $fhirString = new FHIRString(value: 'test');
        $result     = $this->evaluate('$this is Any', $fhirString);
        self::assertSame(1, $result->count());
    }

    public function testIsOperatorWithIntegerDecimalCompatibility(): void
    {
        // Per FHIRPath spec, `is` is strict type identity — Integer is NOT Decimal.
        // Implicit integer→decimal promotion applies in arithmetic/comparison contexts, not `is`.
        $result = $this->evaluate('42 is decimal', null);
        self::assertSame(1, $result->count());
        self::assertFalse($result->first());

        $fhirInteger = new FHIRInteger(value: 100);
        $result      = $this->evaluate('$this is decimal', $fhirInteger);
        self::assertSame(1, $result->count());
        self::assertFalse($result->first());
    }

    public function testAsOperatorWithPrimitiveValues(): void
    {
        // FHIRPath spec: 'as' is a strict type filter — returns the item only when the type matches.

        // integer as integer → matches, returns 42
        $result = $this->evaluate('42 as integer', null);
        self::assertSame(1, $result->count());
        self::assertSame(42, $result->first());

        // string as string → matches, returns the string
        $result = $this->evaluate("'hello' as string", null);
        self::assertSame(1, $result->count());
        self::assertSame('hello', $result->first());

        // boolean as boolean → matches, returns true
        $result = $this->evaluate('true as boolean', null);
        self::assertSame(1, $result->count());
        self::assertTrue($result->first());

        // integer as string → type mismatch → empty (not a cast)
        $result = $this->evaluate('42 as string', null);
        self::assertTrue($result->isEmpty());

        // string as integer → type mismatch → empty
        $result = $this->evaluate("'100' as integer", null);
        self::assertTrue($result->isEmpty());
    }

    public function testAsOperatorWithFHIRPrimitives(): void
    {
        // FHIRPath spec: 'as' is a strict type filter — the FHIR primitive is unwrapped
        // to its PHP scalar by normalizeValue(), and the type check is applied to that scalar.

        // FHIRInteger (unwraps to int) as integer → matches → returns the integer value
        $fhirInteger = new FHIRInteger(value: 42);
        $result      = $this->evaluate('$this as integer', $fhirInteger);
        self::assertSame(1, $result->count());
        self::assertSame(42, $result->first());

        // FHIRString (unwraps to string) as string → matches → returns the string value
        $fhirString = new FHIRString(value: 'test');
        $result     = $this->evaluate('$this as string', $fhirString);
        self::assertSame(1, $result->count());
        self::assertSame('test', $result->first());

        // FHIRInteger as string → type mismatch → empty (not a cast)
        $fhirInteger2 = new FHIRInteger(value: 42);
        $result       = $this->evaluate('$this as string', $fhirInteger2);
        self::assertTrue($result->isEmpty());
    }

    public function testAsOperatorSkipsInvalidCasts(): void
    {
        // Invalid cast should return empty collection (FHIRPath semantics)
        $result = $this->evaluate("'not-a-number' as integer", null);
        self::assertSame(0, $result->count());

        $result = $this->evaluate("'invalid' as boolean", null);
        self::assertSame(0, $result->count());
    }

    public function testIsOperatorCaseInsensitive(): void
    {
        // Type checking should be case-insensitive
        $result = $this->evaluate('42 is Integer', null);
        self::assertSame(1, $result->count());

        $result = $this->evaluate("'hello' is String", null);
        self::assertSame(1, $result->count());

        $result = $this->evaluate('true is Boolean', null);
        self::assertSame(1, $result->count());
    }

    public function testAsOperatorCaseInsensitive(): void
    {
        // Type filtering with 'as' should be case-insensitive for the type name.

        // string as String (case-insensitive) → matches → returns the string
        $result = $this->evaluate("'hello' as String", null);
        self::assertSame(1, $result->count());
        self::assertSame('hello', $result->first());

        // integer as Integer (case-insensitive) → matches → returns the integer
        $result = $this->evaluate('42 as Integer', null);
        self::assertSame(1, $result->count());
        self::assertSame(42, $result->first());

        // boolean as Boolean → matches → returns true
        $result = $this->evaluate('true as Boolean', null);
        self::assertSame(1, $result->count());
        self::assertTrue($result->first());
    }

    public function testIsOperatorWithMultiItemCollectionThrows(): void
    {
        // FHIRPath spec: 'is' requires a single-item collection; multi-item → error.
        $data = (object) [
            'values' => [42, 'hello', true, 3.14],
        ];

        $this->expectException(EvaluationException::class);
        $this->evaluate('values is integer', $data);
    }

    public function testIsOperatorWithSingleItemCollection(): void
    {
        // Single item in collection → boolean result.
        $data = (object) ['value' => 42];

        $result = $this->evaluate('value is integer', $data);
        self::assertSame(1, $result->count());
        self::assertTrue($result->first());

        $result = $this->evaluate('value is string', $data);
        self::assertSame(1, $result->count());
        self::assertFalse($result->first());
    }

    public function testAsOperatorWithCollections(): void
    {
        // FHIRPath spec: 'as' on a multi-item collection is an execution error.
        $data = (object) [
            'values' => [1, 2, 3],
        ];

        $this->expectException(EvaluationException::class);
        $this->evaluate('values as string', $data);
    }

    public function testIsOperatorWithFHIRPrimitiveCollectionMultiItemThrows(): void
    {
        // Multi-item collection with 'is' → exception per FHIRPath spec.
        $data = (object) [
            'items' => [
                new FHIRString(value: 'test'),
                new FHIRInteger(value: 42),
                new FHIRBoolean(value: true),
                new FHIRDecimal(value: 3.14),
            ],
        ];

        $this->expectException(EvaluationException::class);
        $this->evaluate('items is string', $data);
    }

    public function testIsOperatorWithSingleFHIRPrimitive(): void
    {
        // Single unwrapped FHIR primitive → boolean result.
        // FHIR primitive wrappers are unwrapped by normalizeValue() when navigated via property.
        $data = (object) ['item' => new FHIRString(value: 'test')];

        $result = $this->evaluate('item is string', $data);
        self::assertSame(1, $result->count());
        self::assertTrue($result->first());
    }

    // -------------------------------------------------------------------------
    // System.* namespace support (task #27)
    // -------------------------------------------------------------------------

    public function testIsOperatorWithSystemBooleanNamespace(): void
    {
        self::assertTrue($this->evaluate('true is System.Boolean', null)->first());
        self::assertFalse($this->evaluate('42 is System.Boolean', null)->first());
    }

    public function testIsOperatorWithSystemIntegerNamespace(): void
    {
        self::assertTrue($this->evaluate('42 is System.Integer', null)->first());
        self::assertFalse($this->evaluate("'hello' is System.Integer", null)->first());
    }

    public function testIsOperatorWithSystemDecimalNamespace(): void
    {
        $resource = ['n' => 3.14];
        self::assertTrue($this->evaluate('n is System.Decimal', $resource)->first());
        self::assertFalse($this->evaluate("'x' is System.Decimal", null)->first());
    }

    public function testIsOperatorWithSystemStringNamespace(): void
    {
        self::assertTrue($this->evaluate("'hello' is System.String", null)->first());
        self::assertFalse($this->evaluate('42 is System.String', null)->first());
    }

    public function testIsOperatorWithSystemDateNamespace(): void
    {
        // System.Date is a string type at runtime
        $resource = ['d' => '2020-06-15'];
        self::assertTrue($this->evaluate('d is System.Date', $resource)->first());
    }

    public function testIsOperatorWithSystemDateTimeNamespace(): void
    {
        $resource = ['d' => '2020-06-15T10:00:00'];
        self::assertTrue($this->evaluate('d is System.DateTime', $resource)->first());
    }

    public function testIsOperatorWithSystemTimeNamespace(): void
    {
        $resource = ['t' => 'T14:30:00'];
        self::assertTrue($this->evaluate('t is System.Time', $resource)->first());
    }

    public function testFunctionCallFormWithSystemNamespace(): void
    {
        // .is(System.Boolean) function-call form
        self::assertTrue($this->evaluate('true.is(System.Boolean)', null)->first());
        self::assertFalse($this->evaluate('42.is(System.Boolean)', null)->first());
    }

    public function testAsOperatorWithSystemNamespace(): void
    {
        // 42 as System.Integer → type matches → returns 42
        $result = $this->evaluate('42 as System.Integer', null);
        self::assertSame(42, $result->first());

        // 42 as System.String → type mismatch → empty
        $result = $this->evaluate('42 as System.String', null);
        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // FHIR.* namespace support (task #28)
    // -------------------------------------------------------------------------

    public function testIsOperatorWithFHIRStringNamespace(): void
    {
        self::assertTrue($this->evaluate("'hello' is FHIR.string", null)->first());
        self::assertFalse($this->evaluate('42 is FHIR.string', null)->first());
    }

    public function testIsOperatorWithFHIRBooleanNamespace(): void
    {
        self::assertTrue($this->evaluate('true is FHIR.boolean', null)->first());
        self::assertFalse($this->evaluate("'hi' is FHIR.boolean", null)->first());
    }

    public function testIsOperatorWithFHIRIntegerNamespace(): void
    {
        self::assertTrue($this->evaluate('42 is FHIR.integer', null)->first());
    }

    public function testIsOperatorWithFHIRDecimalNamespace(): void
    {
        $resource = ['n' => 1.5];
        self::assertTrue($this->evaluate('n is FHIR.decimal', $resource)->first());
    }

    public function testFunctionCallFormWithFHIRNamespace(): void
    {
        self::assertTrue($this->evaluate("'world'.is(FHIR.string)", null)->first());
    }

    public function testAsOperatorWithFHIRNamespace(): void
    {
        // 42 as FHIR.integer → type matches → returns 42
        $result = $this->evaluate('42 as FHIR.integer', null);
        self::assertSame(42, $result->first());

        // 42 as FHIR.string → type mismatch → empty
        $result = $this->evaluate('42 as FHIR.string', null);
        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // ofType() with namespaced type specifiers
    // -------------------------------------------------------------------------

    public function testOfTypeWithSystemNamespace(): void
    {
        $resource = ['items' => [42, 'hello', true, 3.14]];
        $result   = $this->evaluate('items.ofType(System.Integer)', $resource);
        self::assertSame([42], $result->toArray());
    }

    public function testOfTypeWithFHIRNamespace(): void
    {
        $resource = ['items' => [42, 'hello', true]];
        $result   = $this->evaluate('items.ofType(FHIR.string)', $resource);
        self::assertSame(['hello'], $result->toArray());
    }

    public function testOfTypeWithSystemBoolean(): void
    {
        $resource = ['items' => [true, 42, 'hello', false]];
        $result   = $this->evaluate('items.ofType(System.Boolean)', $resource);
        self::assertSame([true, false], $result->toArray());
    }
}
