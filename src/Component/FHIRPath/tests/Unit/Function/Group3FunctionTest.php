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
 * Tests for Group 3 FHIRPath tree-navigation functions:
 * children(), descendants()
 *
 * @author FHIR Tools Contributors
 */
final class Group3FunctionTest extends TestCase
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
    // children()
    // -------------------------------------------------------------------------

    public function testChildrenReturnsEmptyForEmptyInput(): void
    {
        $result = $this->evaluate('{}.children()', null);

        self::assertTrue($result->isEmpty());
    }

    public function testChildrenReturnsEmptyForScalar(): void
    {
        // Scalars have no children
        $result = $this->evaluate('value.children()', ['value' => 42]);

        self::assertTrue($result->isEmpty());
    }

    public function testChildrenReturnsAllPropertyValues(): void
    {
        $resource = ['a' => 1, 'b' => 2, 'c' => 3];
        $result   = $this->evaluate('children()', $resource);

        self::assertSame(3, $result->count());
        self::assertSame([1, 2, 3], $result->toArray());
    }

    public function testChildrenUnwrapsListValuedProperties(): void
    {
        // 'tags' is a list → each element becomes its own child item
        $resource = ['id' => 'x', 'tags' => ['a', 'b', 'c']];
        $result   = $this->evaluate('children()', $resource);

        // 'id' contributes 1, 'tags' list contributes 3 → 4 total
        self::assertSame(4, $result->count());
        self::assertSame(['x', 'a', 'b', 'c'], $result->toArray());
    }

    public function testChildrenOnNestedObject(): void
    {
        $name     = ['family' => 'Smith', 'given' => ['John']];
        $resource = ['name' => [$name]];

        // children() of the outer resource: 'name' list is unwrapped to its single element
        $outer = $this->evaluate('children()', $resource);
        self::assertSame(1, $outer->count());
        self::assertSame($name, $outer->first());

        // children() of the HumanName: 'family' + 'given' element 'John'
        $inner = $this->evaluate('name.children()', $resource);
        self::assertSame(2, $inner->count());
        self::assertSame('Smith', $inner->first());
    }

    public function testChildrenOnFlattenedListItems(): void
    {
        // Each item in the name list has its own children
        $resource = [
            'name' => [
                ['family' => 'Smith', 'use' => 'official'],
                ['family' => 'Smythe', 'use' => 'nickname'],
            ],
        ];

        // name.children() = all children of each HumanName item, merged
        // Each HumanName has 2 properties → 4 children total
        $result = $this->evaluate('name.children()', $resource);
        self::assertSame(4, $result->count());
    }

    public function testChildrenReturnsEmptyForEmptyString(): void
    {
        $result = $this->evaluate("''.children()", null);

        self::assertTrue($result->isEmpty());
    }

    public function testChildrenSkipsNullPropertyValues(): void
    {
        $resource = ['a' => 1, 'b' => null, 'c' => 3];
        $result   = $this->evaluate('children()', $resource);

        // 'b' is null → skipped
        self::assertSame(2, $result->count());
        self::assertSame([1, 3], $result->toArray());
    }

    // -------------------------------------------------------------------------
    // descendants()
    // -------------------------------------------------------------------------

    public function testDescendantsReturnsEmptyForEmptyInput(): void
    {
        $result = $this->evaluate('{}.descendants()', null);

        self::assertTrue($result->isEmpty());
    }

    public function testDescendantsReturnsEmptyForScalar(): void
    {
        $result = $this->evaluate('value.descendants()', ['value' => 42]);

        self::assertTrue($result->isEmpty());
    }

    public function testDescendantsDoesNotIncludeRootItems(): void
    {
        // descendants() never includes the input items themselves
        $resource = ['a' => 1, 'b' => 2];
        $result   = $this->evaluate('descendants()', $resource);

        // Only the values (1, 2), not $resource itself
        self::assertSame(2, $result->count());
        self::assertFalse(in_array($resource, $result->toArray(), true));
    }

    public function testDescendantsTraversesDeepTree(): void
    {
        // Three-level tree
        $resource = [
            'level1' => [
                'level2' => [
                    'leaf' => 'value',
                ],
            ],
        ];

        $result = $this->evaluate('descendants()', $resource);

        // level1 object + level2 object + 'value' scalar = 3
        self::assertSame(3, $result->count());
    }

    public function testDescendantsFlattensListsAtEachLevel(): void
    {
        $resource = [
            'name' => [
                ['family' => 'Smith'],
                ['family' => 'Jones'],
            ],
        ];

        // descendants of $resource:
        //   level 1: HumanName1, HumanName2  (name list unwrapped)
        //   level 2: 'Smith', 'Jones'         (family property of each)
        $result = $this->evaluate('descendants()', $resource);

        self::assertSame(4, $result->count());
        self::assertContains('Smith', $result->toArray());
        self::assertContains('Jones', $result->toArray());
    }

    public function testDescendantsIsSupersetOfChildren(): void
    {
        $resource = [
            'id'   => 'root',
            'name' => ['family' => 'Smith'],
        ];

        $children    = $this->evaluate('children()', $resource);
        $descendants = $this->evaluate('descendants()', $resource);

        // descendants must have at least as many items as children
        self::assertGreaterThanOrEqual($children->count(), $descendants->count());
    }

    public function testDescendantsHandlesCyclicObjectGraph(): void
    {
        // PHP objects can form true circular references
        $nodeA       = new \stdClass();
        $nodeB       = new \stdClass();
        $nodeA->id   = 'a';
        $nodeA->next = $nodeB;
        $nodeB->id   = 'b';
        $nodeB->next = $nodeA; // cycle: a → b → a

        // Should terminate and return each node's descendants once
        $result = $this->evaluate('descendants()', $nodeA);

        // $nodeB + 'b' + 'a' (id of nodeA reached via nodeB.next.id)
        // The cycle guard on spl_object_hash prevents revisiting nodeA as an object
        self::assertGreaterThan(0, $result->count());
    }
}
