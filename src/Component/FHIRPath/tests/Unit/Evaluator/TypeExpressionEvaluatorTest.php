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
        // Integer is integer
        $result = $this->evaluate('42 is integer', null);
        self::assertSame(1, $result->count());
        self::assertSame(42, $result->first());

        // String is string
        $result = $this->evaluate("'hello' is string", null);
        self::assertSame(1, $result->count());
        self::assertSame('hello', $result->first());

        // Boolean is boolean
        $result = $this->evaluate('true is boolean', null);
        self::assertSame(1, $result->count());
        self::assertTrue($result->first());
    }

    public function testIsOperatorWithFHIRPrimitives(): void
    {
        // FHIRString is string
        $fhirString = new FHIRString(value: 'test');
        $result     = $this->evaluate('$this is string', $fhirString);
        self::assertSame(1, $result->count());
        self::assertInstanceOf(FHIRString::class, $result->first());

        // FHIRInteger is integer
        $fhirInteger = new FHIRInteger(value: 123);
        $result      = $this->evaluate('$this is integer', $fhirInteger);
        self::assertSame(1, $result->count());
        self::assertInstanceOf(FHIRInteger::class, $result->first());

        // FHIRBoolean is boolean
        $fhirBoolean = new FHIRBoolean(value: true);
        $result      = $this->evaluate('$this is boolean', $fhirBoolean);
        self::assertSame(1, $result->count());
        self::assertInstanceOf(FHIRBoolean::class, $result->first());
    }

    public function testIsOperatorWithTypeNegativeMatch(): void
    {
        // Integer is not string
        $result = $this->evaluate('42 is string', null);
        self::assertSame(0, $result->count());

        // String is not integer
        $result = $this->evaluate("'hello' is integer", null);
        self::assertSame(0, $result->count());
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
        // Integer is compatible with decimal
        $result = $this->evaluate('42 is decimal', null);
        self::assertSame(1, $result->count());
        self::assertSame(42, $result->first());

        $fhirInteger = new FHIRInteger(value: 100);
        $result      = $this->evaluate('$this is decimal', $fhirInteger);
        self::assertSame(1, $result->count());
    }

    public function testAsOperatorWithPrimitiveValues(): void
    {
        // Cast integer to string
        $result = $this->evaluate('42 as string', null);
        self::assertSame(1, $result->count());
        self::assertSame('42', $result->first());

        // Cast string to integer
        $result = $this->evaluate("'100' as integer", null);
        self::assertSame(1, $result->count());
        self::assertSame(100, $result->first());

        // Cast string to boolean
        $result = $this->evaluate("'true' as boolean", null);
        self::assertSame(1, $result->count());
        self::assertTrue($result->first());
    }

    public function testAsOperatorWithFHIRPrimitives(): void
    {
        // Cast FHIRInteger to string
        $fhirInteger = new FHIRInteger(value: 42);
        $result      = $this->evaluate('$this as string', $fhirInteger);
        self::assertSame(1, $result->count());
        self::assertSame('42', $result->first());

        // Cast FHIRString to integer
        $fhirString = new FHIRString(value: '100');
        $result     = $this->evaluate('$this as integer', $fhirString);
        self::assertSame(1, $result->count());
        self::assertSame(100, $result->first());
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
        // Type casting should be case-insensitive
        $result = $this->evaluate('42 as String', null);
        self::assertSame(1, $result->count());
        self::assertSame('42', $result->first());

        $result = $this->evaluate("'100' as Integer", null);
        self::assertSame(1, $result->count());
        self::assertSame(100, $result->first());
    }

    public function testIsOperatorWithCollections(): void
    {
        // Create a collection with mixed types
        $data = (object) [
            'values' => [42, 'hello', true, 3.14],
        ];

        // Filter only integers
        $result = $this->evaluate('values is integer', $data);
        self::assertSame(1, $result->count());
        self::assertSame(42, $result->first());
    }

    public function testAsOperatorWithCollections(): void
    {
        // Create a collection of integers
        $data = (object) [
            'values' => [1, 2, 3],
        ];

        // Cast all integers to strings
        $result = $this->evaluate('values as string', $data);
        self::assertSame(3, $result->count());
        self::assertSame(['1', '2', '3'], $result->toArray());
    }

    public function testIsOperatorWithFHIRPrimitiveCollection(): void
    {
        // Create a collection with mixed FHIR primitives
        $data = (object) [
            'items' => [
                new FHIRString(value: 'test'),
                new FHIRInteger(value: 42),
                new FHIRBoolean(value: true),
                new FHIRDecimal(value: 3.14),
            ],
        ];

        // Filter only FHIRString items
        $result = $this->evaluate('items is string', $data);
        self::assertSame(1, $result->count());
        self::assertInstanceOf(FHIRString::class, $result->first());
    }
}
