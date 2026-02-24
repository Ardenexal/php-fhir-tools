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
 * Tests for Group 1 FHIRPath functions:
 * subsetOf(), supersetOf(), isDistinct(), not(), repeat()
 *
 * @author FHIR Tools Contributors
 */
final class Group1FunctionTest extends TestCase
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

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function evaluate(string $expression, mixed $resource): Collection
    {
        $tokens = $this->lexer->tokenize($expression);
        $ast    = $this->parser->parse($tokens);

        return $this->evaluator->evaluate($ast, $resource);
    }

    // -------------------------------------------------------------------------
    // not()
    // -------------------------------------------------------------------------

    public function testNotReturnsFalseForTrue(): void
    {
        $result = $this->evaluate('true.not()', null);

        self::assertTrue($result->isSingle());
        self::assertFalse($result->first());
    }

    public function testNotReturnsTrueForFalse(): void
    {
        $result = $this->evaluate('false.not()', null);

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    public function testNotReturnsEmptyForEmpty(): void
    {
        $result = $this->evaluate('{}.not()', null);

        self::assertTrue($result->isEmpty());
    }

    public function testNotReturnsEmptyForNonBoolean(): void
    {
        $result = $this->evaluate("'hello'.not()", null);

        self::assertTrue($result->isEmpty());
    }

    public function testNotCanChainWithComparison(): void
    {
        // (1 = 2).not() → true
        $result = $this->evaluate('(1 = 2).not()', null);

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    // -------------------------------------------------------------------------
    // isDistinct()
    // -------------------------------------------------------------------------

    public function testIsDistinctReturnsTrueForEmptyCollection(): void
    {
        $result = $this->evaluate('{}.isDistinct()', null);

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    public function testIsDistinctReturnsTrueForSingleItem(): void
    {
        $result = $this->evaluate('1.isDistinct()', null);

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    public function testIsDistinctReturnsTrueForAllDifferentItems(): void
    {
        // (1 | 2 | 3).isDistinct() → true
        $result = $this->evaluate('(1 | 2 | 3).isDistinct()', null);

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    public function testIsDistinctReturnsFalseForDuplicates(): void
    {
        // distinct() de-dupes before isDistinct sees them — need a path that returns dupes
        $resource = ['tags' => ['a', 'a', 'b']];
        $result   = $this->evaluate('tags.isDistinct()', $resource);

        self::assertTrue($result->isSingle());
        self::assertFalse($result->first());
    }

    public function testIsDistinctReturnsTrueForNoDuplicates(): void
    {
        $resource = ['tags' => ['a', 'b', 'c']];
        $result   = $this->evaluate('tags.isDistinct()', $resource);

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    // -------------------------------------------------------------------------
    // subsetOf()
    // -------------------------------------------------------------------------

    public function testSubsetOfReturnsTrueForSubset(): void
    {
        // (1 | 2).subsetOf(1 | 2 | 3) → true
        $resource = ['small' => [1, 2], 'large' => [1, 2, 3]];
        $result   = $this->evaluate('small.subsetOf(large)', $resource);

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    public function testSubsetOfReturnsFalseForNonSubset(): void
    {
        $resource = ['a' => [1, 2, 4], 'b' => [1, 2, 3]];
        $result   = $this->evaluate('a.subsetOf(b)', $resource);

        self::assertTrue($result->isSingle());
        self::assertFalse($result->first());
    }

    public function testSubsetOfReturnsTrueForEmptyInput(): void
    {
        $resource = ['other' => [1, 2, 3]];
        $result   = $this->evaluate('{}.subsetOf(other)', $resource);

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    public function testSubsetOfReturnsFalseWhenOtherIsEmpty(): void
    {
        $resource = ['items' => [1]];
        $result   = $this->evaluate('items.subsetOf({})', $resource);

        self::assertTrue($result->isSingle());
        self::assertFalse($result->first());
    }

    public function testSubsetOfReturnsTrueForIdenticalCollections(): void
    {
        $resource = ['a' => [1, 2, 3], 'b' => [1, 2, 3]];
        $result   = $this->evaluate('a.subsetOf(b)', $resource);

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    // -------------------------------------------------------------------------
    // supersetOf()
    // -------------------------------------------------------------------------

    public function testSupersetOfReturnsTrueForSuperset(): void
    {
        $resource = ['large' => [1, 2, 3], 'small' => [1, 2]];
        $result   = $this->evaluate('large.supersetOf(small)', $resource);

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    public function testSupersetOfReturnsFalseForNonSuperset(): void
    {
        $resource = ['a' => [1, 2], 'b' => [1, 2, 3]];
        $result   = $this->evaluate('a.supersetOf(b)', $resource);

        self::assertTrue($result->isSingle());
        self::assertFalse($result->first());
    }

    public function testSupersetOfReturnsTrueWhenOtherIsEmpty(): void
    {
        $resource = ['items' => [1, 2]];
        $result   = $this->evaluate('items.supersetOf({})', $resource);

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    public function testSupersetOfReturnsFalseForEmptyInputAndNonEmptyOther(): void
    {
        $resource = ['other' => [1, 2]];
        $result   = $this->evaluate('{}.supersetOf(other)', $resource);

        self::assertTrue($result->isSingle());
        self::assertFalse($result->first());
    }

    public function testSupersetOfAndSubsetOfAreInverseForEqualCollections(): void
    {
        $resource = ['a' => [1, 2, 3], 'b' => [1, 2, 3]];

        $subResult   = $this->evaluate('a.subsetOf(b)', $resource);
        $superResult = $this->evaluate('a.supersetOf(b)', $resource);

        self::assertTrue($subResult->first());
        self::assertTrue($superResult->first());
    }

    // -------------------------------------------------------------------------
    // repeat()
    // -------------------------------------------------------------------------

    public function testRepeatWithNoChildrenIsStable(): void
    {
        // Scalar has no 'children' property → repeat returns just the input
        $resource = ['value' => 1];
        $result   = $this->evaluate('value.repeat(nothing)', $resource);

        // nothing produces empty → repeat returns original single item
        self::assertSame(1, $result->count());
    }

    public function testRepeatBuildsTransitiveClosure(): void
    {
        // Build a simple tree: a → b → c
        $c = ['id' => 'c', 'child' => null];
        $b = ['id' => 'b', 'child' => $c];
        $a = ['id' => 'a', 'child' => $b];

        // repeat(child) from a should give [a, b, c]
        $result = $this->evaluate('repeat(child)', $a);

        // $a itself is the input, so result = {a, b, c}
        self::assertSame(3, $result->count());
    }

    public function testRepeatReturnsEmptyForEmptyInput(): void
    {
        $result = $this->evaluate('{}.repeat(id)', null);

        self::assertTrue($result->isEmpty());
    }

    public function testRepeatDoesNotRepeatDuplicates(): void
    {
        // Cycle: a → b → a (object identity prevents infinite loop)
        $a          = ['id' => 'a'];
        $b          = ['id' => 'b'];
        $a['child'] = $b;
        $b['child'] = $a;

        // Objects are tracked by key, so this terminates
        $result = $this->evaluate('repeat(child)', $a);

        // Should contain $a and $b, no infinite loop
        self::assertSame(2, $result->count());
    }
}
