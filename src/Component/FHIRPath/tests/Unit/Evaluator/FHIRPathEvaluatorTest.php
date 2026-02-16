<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Evaluator;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator
 */
final class FHIRPathEvaluatorTest extends TestCase
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

    public function testEvaluateLiteralString(): void
    {
        $result = $this->evaluate("'hello'", null);

        self::assertSame(1, $result->count());
        self::assertSame('hello', $result->first());
    }

    public function testEvaluateLiteralNumber(): void
    {
        $result = $this->evaluate('42', null);

        self::assertSame(1, $result->count());
        self::assertSame(42, $result->first());
    }

    public function testEvaluateLiteralBoolean(): void
    {
        $resultTrue  = $this->evaluate('true', null);
        $resultFalse = $this->evaluate('false', null);

        self::assertTrue($resultTrue->first());
        self::assertFalse($resultFalse->first());
    }

    public function testSimplePropertyAccess(): void
    {
        $resource = ['name' => 'John'];
        $result   = $this->evaluate('name', $resource);

        self::assertSame(1, $result->count());
        self::assertSame('John', $result->first());
    }

    public function testNestedPropertyAccess(): void
    {
        $resource = [
            'name' => [
                'given' => 'John',
            ],
        ];
        $result = $this->evaluate('name.given', $resource);

        self::assertSame(1, $result->count());
        self::assertSame('John', $result->first());
    }

    public function testArrayPropertyAccess(): void
    {
        $resource = [
            'name' => [
                ['given' => 'John'],
                ['given' => 'Jane'],
            ],
        ];
        $result = $this->evaluate('name.given', $resource);

        self::assertSame(2, $result->count());
        self::assertSame(['John', 'Jane'], $result->toArray());
    }

    public function testMissingPropertyReturnsEmpty(): void
    {
        $resource = ['name' => 'John'];
        $result   = $this->evaluate('age', $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testEmptyPropagation(): void
    {
        $resource = [];
        $result   = $this->evaluate('name.given.first', $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testArithmeticAddition(): void
    {
        $result = $this->evaluate('5 + 3', null);

        self::assertSame(1, $result->count());
        self::assertSame(8, $result->first());
    }

    public function testArithmeticSubtraction(): void
    {
        $result = $this->evaluate('10 - 3', null);

        self::assertSame(7, $result->first());
    }

    public function testArithmeticMultiplication(): void
    {
        $result = $this->evaluate('4 * 5', null);

        self::assertSame(20, $result->first());
    }

    public function testArithmeticDivision(): void
    {
        $result = $this->evaluate('15 / 3', null);

        self::assertSame(5.0, $result->first());
    }

    public function testArithmeticIntegerDivision(): void
    {
        $result = $this->evaluate('17 div 5', null);

        self::assertSame(3, $result->first());
    }

    public function testArithmeticModulo(): void
    {
        $result = $this->evaluate('17 mod 5', null);

        self::assertSame(2, $result->first());
    }

    public function testUnaryNegation(): void
    {
        $result = $this->evaluate('-5', null);

        self::assertSame(-5, $result->first());
    }

    public function testUnaryPositive(): void
    {
        $result = $this->evaluate('+5', null);

        self::assertSame(5, $result->first());
    }

    public function testEqualityTrue(): void
    {
        $result = $this->evaluate('5 = 5', null);

        self::assertTrue($result->first());
    }

    public function testEqualityFalse(): void
    {
        $result = $this->evaluate('5 = 3', null);

        self::assertFalse($result->first());
    }

    public function testInequalityTrue(): void
    {
        $result = $this->evaluate('5 != 3', null);

        self::assertTrue($result->first());
    }

    public function testLessThan(): void
    {
        $result = $this->evaluate('3 < 5', null);

        self::assertTrue($result->first());
    }

    public function testGreaterThan(): void
    {
        $result = $this->evaluate('5 > 3', null);

        self::assertTrue($result->first());
    }

    public function testLessThanOrEqual(): void
    {
        $result1 = $this->evaluate('3 <= 5', null);
        $result2 = $this->evaluate('5 <= 5', null);

        self::assertTrue($result1->first());
        self::assertTrue($result2->first());
    }

    public function testGreaterThanOrEqual(): void
    {
        $result1 = $this->evaluate('5 >= 3', null);
        $result2 = $this->evaluate('5 >= 5', null);

        self::assertTrue($result1->first());
        self::assertTrue($result2->first());
    }

    public function testStringConcatenation(): void
    {
        $result = $this->evaluate("'Hello' & ' ' & 'World'", null);

        self::assertSame('Hello World', $result->first());
    }

    public function testLogicalAndTrue(): void
    {
        $result = $this->evaluate('true and true', null);

        self::assertTrue($result->first());
    }

    public function testLogicalAndFalse(): void
    {
        $result = $this->evaluate('true and false', null);

        self::assertFalse($result->first());
    }

    public function testLogicalOrTrue(): void
    {
        $result = $this->evaluate('false or true', null);

        self::assertTrue($result->first());
    }

    public function testLogicalOrFalse(): void
    {
        $result = $this->evaluate('false or false', null);

        self::assertFalse($result->first());
    }

    public function testLogicalXorTrue(): void
    {
        $result = $this->evaluate('true xor false', null);

        self::assertTrue($result->first());
    }

    public function testLogicalXorFalse(): void
    {
        $result = $this->evaluate('true xor true', null);

        self::assertFalse($result->first());
    }

    public function testImpliesTrueTrue(): void
    {
        $result = $this->evaluate('true implies true', null);

        self::assertTrue($result->first());
    }

    public function testImpliesTrueFalse(): void
    {
        $result = $this->evaluate('true implies false', null);

        self::assertFalse($result->first());
    }

    public function testImpliesFalseAnything(): void
    {
        $result1 = $this->evaluate('false implies true', null);
        $result2 = $this->evaluate('false implies false', null);

        self::assertTrue($result1->first());
        self::assertTrue($result2->first());
    }

    public function testUnionOperator(): void
    {
        $result = $this->evaluate('{1, 2} | {2, 3}', null);

        // Union removes duplicates
        self::assertSame(3, $result->count());
        self::assertContains(1, $result->toArray());
        self::assertContains(2, $result->toArray());
        self::assertContains(3, $result->toArray());
    }

    public function testCollectionLiteralEmpty(): void
    {
        $result = $this->evaluate('{}', null);

        self::assertTrue($result->isEmpty());
    }

    public function testCollectionLiteralSingle(): void
    {
        $result = $this->evaluate('{42}', null);

        self::assertSame(1, $result->count());
        self::assertSame(42, $result->first());
    }

    public function testCollectionLiteralMultiple(): void
    {
        $result = $this->evaluate('{1, 2, 3}', null);

        self::assertSame(3, $result->count());
        self::assertSame([1, 2, 3], $result->toArray());
    }

    public function testIndexer(): void
    {
        $resource = ['items' => ['a', 'b', 'c']];
        $result   = $this->evaluate('items[1]', $resource);

        self::assertSame('b', $result->first());
    }

    public function testIndexerOutOfBounds(): void
    {
        $resource = ['items' => ['a', 'b']];
        $result   = $this->evaluate('items[5]', $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testReservedIdentifierThis(): void
    {
        $resource = ['value' => 42];
        $context  = new EvaluationContext();
        $context->setVariable('this', $resource);

        $tokens = $this->lexer->tokenize('$this');
        $ast    = $this->parser->parse($tokens);
        $result = $this->evaluator->evaluate($ast, $resource, $context);

        self::assertSame($resource, $result->first());
    }

    public function testExternalConstant(): void
    {
        $context = new EvaluationContext();
        $context->setExternalConstant('ucum', 'http://unitsofmeasure.org');

        $tokens = $this->lexer->tokenize('%ucum');
        $ast    = $this->parser->parse($tokens);
        $result = $this->evaluator->evaluate($ast, null, $context);

        self::assertSame('http://unitsofmeasure.org', $result->first());
    }

    public function testComplexNestedPath(): void
    {
        $resource = [
            'name' => [
                [
                    'use'   => 'official',
                    'given' => ['John', 'Q'],
                ],
                [
                    'use'   => 'nickname',
                    'given' => ['Johnny'],
                ],
            ],
        ];

        $result = $this->evaluate('name.given', $resource);

        // Should flatten all given names
        self::assertSame(3, $result->count());
        self::assertContains('John', $result->toArray());
        self::assertContains('Q', $result->toArray());
        self::assertContains('Johnny', $result->toArray());
    }

    public function testObjectPropertyAccess(): void
    {
        $object = new class () {
            public string $name = 'Test';
        };

        $result = $this->evaluate('name', $object);

        self::assertSame('Test', $result->first());
    }

    public function testObjectGetterMethod(): void
    {
        $object = new class () {
            private string $name = 'Test';

            public function getName(): string
            {
                return $this->name;
            }
        };

        $result = $this->evaluate('name', $object);

        self::assertSame('Test', $result->first());
    }
}
