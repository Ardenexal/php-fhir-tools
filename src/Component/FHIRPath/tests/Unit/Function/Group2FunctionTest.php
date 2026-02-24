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
 * Tests for Group 2 FHIRPath string functions:
 * replaceMatches(), toChars()
 *
 * @author FHIR Tools Contributors
 */
final class Group2FunctionTest extends TestCase
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
    // replaceMatches()
    // -------------------------------------------------------------------------

    public function testReplaceMatchesBasicPattern(): void
    {
        $result = $this->evaluate("'hello world'.replaceMatches('[aeiou]', '*')", null);

        self::assertTrue($result->isSingle());
        self::assertSame('h*ll* w*rld', $result->first());
    }

    public function testReplaceMatchesWithCaptureGroupBackreference(): void
    {
        // Wrap each word in angle brackets using a capture group
        $result = $this->evaluate("'foo bar'.replaceMatches('(\\\\w+)', '<$1>')", null);

        self::assertTrue($result->isSingle());
        self::assertSame('<foo> <bar>', $result->first());
    }

    public function testReplaceMatchesReplacesAllOccurrences(): void
    {
        $result = $this->evaluate("'aababc'.replaceMatches('a', 'x')", null);

        self::assertTrue($result->isSingle());
        self::assertSame('xxbxbc', $result->first());
    }

    public function testReplaceMatchesNoMatch(): void
    {
        $result = $this->evaluate("'hello'.replaceMatches('[0-9]', 'X')", null);

        self::assertTrue($result->isSingle());
        self::assertSame('hello', $result->first());
    }

    public function testReplaceMatchesEmptySubstitution(): void
    {
        // Deletes all digits
        $result = $this->evaluate("'abc123def'.replaceMatches('[0-9]+', '')", null);

        self::assertTrue($result->isSingle());
        self::assertSame('abcdef', $result->first());
    }

    public function testReplaceMatchesReturnsEmptyForEmptyInput(): void
    {
        $result = $this->evaluate("{}.replaceMatches('a', 'b')", null);

        self::assertTrue($result->isEmpty());
    }

    public function testReplaceMatchesWithResourceProperty(): void
    {
        $resource = ['name' => 'John Smith'];
        $result   = $this->evaluate("name.replaceMatches('\\\\s+', '_')", $resource);

        self::assertTrue($result->isSingle());
        self::assertSame('John_Smith', $result->first());
    }

    // -------------------------------------------------------------------------
    // toChars()
    // -------------------------------------------------------------------------

    public function testToCharsReturnsIndividualCharacters(): void
    {
        $result = $this->evaluate("'abc'.toChars()", null);

        self::assertSame(3, $result->count());
        self::assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testToCharsSingleCharacter(): void
    {
        $result = $this->evaluate("'x'.toChars()", null);

        self::assertTrue($result->isSingle());
        self::assertSame('x', $result->first());
    }

    public function testToCharsEmptyStringReturnsEmpty(): void
    {
        $result = $this->evaluate("''.toChars()", null);

        self::assertTrue($result->isEmpty());
    }

    public function testToCharsEmptyCollectionReturnsEmpty(): void
    {
        $result = $this->evaluate('{}.toChars()', null);

        self::assertTrue($result->isEmpty());
    }

    public function testToCharsWithResourceProperty(): void
    {
        $resource = ['code' => 'AB'];
        $result   = $this->evaluate('code.toChars()', $resource);

        self::assertSame(2, $result->count());
        self::assertSame(['A', 'B'], $result->toArray());
    }

    public function testToCharsCountMatchesLength(): void
    {
        $resource = ['text' => 'hello'];
        $chars    = $this->evaluate('text.toChars()', $resource);
        $length   = $this->evaluate('text.length()', $resource);

        self::assertSame($length->first(), $chars->count());
    }
}
