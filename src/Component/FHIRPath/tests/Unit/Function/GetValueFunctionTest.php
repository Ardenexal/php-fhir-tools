<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive\FHIRString;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the FHIR getValue() function.
 *
 * @author FHIR Tools Contributors
 */
final class GetValueFunctionTest extends TestCase
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
    // PHP scalars — already the system value, returned as-is
    // -------------------------------------------------------------------------

    public function testGetValueOnPhpString(): void
    {
        $resource = ['name' => 'Alice'];
        $result   = $this->evaluate('name.getValue()', $resource);
        self::assertSame('Alice', $result->first());
    }

    public function testGetValueOnPhpInteger(): void
    {
        self::assertSame(42, $this->evaluate('42.getValue()', null)->first());
    }

    public function testGetValueOnPhpBoolean(): void
    {
        self::assertTrue($this->evaluate('true.getValue()', null)->first());
        self::assertFalse($this->evaluate('false.getValue()', null)->first());
    }

    public function testGetValueOnPhpFloat(): void
    {
        $resource = ['score' => 3.14];
        self::assertSame(3.14, $this->evaluate('score.getValue()', $resource)->first());
    }

    // -------------------------------------------------------------------------
    // FHIR primitive wrappers — unwrap to ->value
    // -------------------------------------------------------------------------

    public function testGetValueUnwrapsFHIRString(): void
    {
        $wrapper = new FHIRString(value: 'hello');
        $result  = $this->evaluate('$this.getValue()', $wrapper);
        self::assertSame('hello', $result->first());
    }

    public function testGetValueUnwrapsFHIRBoolean(): void
    {
        $wrapper = new FHIRBoolean(value: true);
        $result  = $this->evaluate('$this.getValue()', $wrapper);
        self::assertTrue($result->first());
    }

    public function testGetValueUnwrapsFHIRInteger(): void
    {
        $wrapper = new FHIRInteger(value: 7);
        $result  = $this->evaluate('$this.getValue()', $wrapper);
        self::assertSame(7, $result->first());
    }

    public function testGetValueUnwrapsFHIRDecimal(): void
    {
        $wrapper = new FHIRDecimal(value: 2.71828);
        $result  = $this->evaluate('$this.getValue()', $wrapper);
        self::assertSame(2.71828, $result->first());
    }

    public function testGetValueOnWrapperWithNullValueReturnsEmpty(): void
    {
        // FHIRString with no value set — getValue() returns empty
        $wrapper = new FHIRString(value: null);
        $result  = $this->evaluate('$this.getValue()', $wrapper);
        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Complex (non-primitive) objects — no underlying system value → empty
    // -------------------------------------------------------------------------

    public function testGetValueOnComplexObjectReturnsEmpty(): void
    {
        // A plain stdClass is not a FHIR primitive wrapper
        $resource = new \stdClass();
        $result   = $this->evaluate('$this.getValue()', $resource);
        self::assertTrue($result->isEmpty());
    }

    public function testGetValueOnAssocArrayReturnsEmpty(): void
    {
        // An associative array is not a scalar primitive
        $resource = ['items' => [['a' => 1]]];
        $result   = $this->evaluate('items.getValue()', $resource);
        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Empty input
    // -------------------------------------------------------------------------

    public function testGetValueOnEmptyInputReturnsEmpty(): void
    {
        self::assertTrue($this->evaluate('{}.getValue()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Chaining and navigation
    // -------------------------------------------------------------------------

    public function testGetValueChainedAfterPropertyNavigation(): void
    {
        // After property navigation, values are already scalars — getValue() passes through
        $resource = ['age' => 30];
        $result   = $this->evaluate('age.getValue()', $resource);
        self::assertSame(30, $result->first());
    }

    public function testGetValueOnWrapperStoredAsProperty(): void
    {
        // Object with a FHIRString property — navigating the property unwraps it,
        // so getValue() receives the already-unwrapped string and returns it as-is
        $obj       = new \stdClass();
        $obj->name = new FHIRString(value: 'Bob');

        $result = $this->evaluate('name.getValue()', $obj);
        // name navigation calls normalizeValue() → 'Bob' (string); getValue() returns 'Bob'
        self::assertSame('Bob', $result->first());
    }

    public function testGetValueWorksOnCollectionOfPrimitives(): void
    {
        // Multiple items: each scalar passes through
        $resource = ['tags' => ['alpha', 'beta', 'gamma']];
        $result   = $this->evaluate('tags.getValue()', $resource);
        self::assertSame(['alpha', 'beta', 'gamma'], $result->toArray());
    }

    // -------------------------------------------------------------------------
    // BackedEnum
    // -------------------------------------------------------------------------

    public function testGetValueUnwracksBackedEnum(): void
    {
        $enum     = GetValueFunctionTestEnum::Active;
        $resource = ['status' => $enum];
        $result   = $this->evaluate('status.getValue()', $resource);
        self::assertSame('active', $result->first());
    }
}

/**
 * Helper BackedEnum for testing getValue() with enum values.
 *
 * @internal
 */
enum GetValueFunctionTestEnum: string
{
    case Active   = 'active';
    case Inactive = 'inactive';
}
