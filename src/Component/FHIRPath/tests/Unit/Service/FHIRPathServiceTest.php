<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Service;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\CompiledExpression;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use PHPUnit\Framework\TestCase;
use Ardenexal\FHIRTools\Component\FHIRPath\Cache\ExpressionCacheInterface;

/**
 * @covers \Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService
 *
 * @author Ardenexal
 */
class FHIRPathServiceTest extends TestCase
{
    private FHIRPathService $service;

    protected function setUp(): void
    {
        $this->service = new FHIRPathService();
    }

    public function testEvaluateSimpleExpression(): void
    {
        $resource = (object) ['name' => 'John'];

        $result = $this->service->evaluate('name', $resource);

        self::assertInstanceOf(Collection::class, $result);
        self::assertSame(1, $result->count());
        self::assertSame('John', $result->first());
    }

    public function testEvaluateLiteralExpression(): void
    {
        $result = $this->service->evaluate("'hello'", null);

        self::assertSame(1, $result->count());
        self::assertSame('hello', $result->first());
    }

    public function testEvaluateWithArray(): void
    {
        $resource = ['name' => ['given' => ['John', 'Jane']]];

        $result = $this->service->evaluate('name.given', $resource);

        self::assertSame(2, $result->count());
        self::assertSame(['John', 'Jane'], $result->toArray());
    }

    public function testEvaluateWithNestedPath(): void
    {
        $resource = (object) [
            'patient' => (object) [
                'name' => (object) [
                    'given' => 'John',
                ],
            ],
        ];

        $result = $this->service->evaluate('patient.name.given', $resource);

        self::assertSame(1, $result->count());
        self::assertSame('John', $result->first());
    }

    public function testEvaluateWithCustomContext(): void
    {
        $resource = (object) ['value' => 42];
        $context  = new EvaluationContext();
        $context->setVariable('custom', 'test');

        $result = $this->service->evaluate('value', $resource, $context);

        self::assertSame(42, $result->first());
    }

    public function testEvaluateThrowsExceptionForInvalidExpression(): void
    {
        $this->expectException(FHIRPathException::class);

        $this->service->evaluate('invalid..expression', null);
    }

    public function testValidateValidExpression(): void
    {
        $isValid = $this->service->validate('name.given');

        self::assertTrue($isValid);
    }

    public function testValidateComplexExpression(): void
    {
        $isValid = $this->service->validate("name.where(use = 'official').given.first()");

        self::assertTrue($isValid);
    }

    public function testValidateInvalidExpression(): void
    {
        $isValid = $this->service->validate('invalid..expression');

        self::assertFalse($isValid);
    }

    public function testValidateEmptyExpression(): void
    {
        $isValid = $this->service->validate('');

        self::assertFalse($isValid);
    }

    public function testCompileExpression(): void
    {
        $compiled = $this->service->compile('name');

        self::assertInstanceOf(CompiledExpression::class, $compiled);
        self::assertSame('name', $compiled->getExpression());
    }

    public function testCompileAndEvaluate(): void
    {
        $compiled = $this->service->compile('name.given');

        $resource1 = (object) ['name' => (object) ['given' => 'John']];
        $resource2 = (object) ['name' => (object) ['given' => 'Jane']];

        $result1 = $compiled->evaluate($resource1);
        $result2 = $compiled->evaluate($resource2);

        self::assertSame('John', $result1->first());
        self::assertSame('Jane', $result2->first());
    }

    public function testCompileMultipleEvaluations(): void
    {
        $compiled = $this->service->compile('value * 2');

        $resources = [
            (object) ['value' => 1],
            (object) ['value' => 2],
            (object) ['value' => 3],
        ];

        $results = [];
        foreach ($resources as $resource) {
            $result    = $compiled->evaluate($resource);
            $results[] = $result->first();
        }

        self::assertSame([2, 4, 6], $results);
    }

    public function testCompileThrowsExceptionForInvalidExpression(): void
    {
        $this->expectException(FHIRPathException::class);

        $this->service->compile('invalid..expression');
    }

    public function testEvaluateWithOperator(): void
    {
        $result = $this->service->evaluate('5 + 3', null);

        self::assertSame(8, $result->first());
    }

    public function testEvaluateWithComparison(): void
    {
        $resource = (object) ['age' => 25];

        $result = $this->service->evaluate('age > 18', $resource);

        self::assertTrue($result->first());
    }

    public function testEvaluateEmptyResult(): void
    {
        $resource = (object) ['name' => 'John'];

        $result = $this->service->evaluate('missing', $resource);

        self::assertSame(0, $result->count());
        self::assertTrue($result->isEmpty());
    }

    public function testCompilePreservesExpressionString(): void
    {
        $expression = "name.where(use = 'official').given.first()";
        $compiled   = $this->service->compile($expression);

        self::assertSame($expression, $compiled->getExpression());
        self::assertSame($expression, (string) $compiled);
    }

    public function testCachingReducesParsing(): void
    {
        $expression = 'name.given';

        // First evaluation - should cache
        $this->service->evaluate($expression, (object) ['name' => (object) ['given' => 'John']]);

        $stats = $this->service->getCacheStats();
        self::assertSame(0, $stats['hits']); // First time is a miss
        self::assertSame(1, $stats['misses']);
        self::assertSame(1, $stats['size']);

        // Second evaluation - should hit cache
        $this->service->evaluate($expression, (object) ['name' => (object) ['given' => 'Jane']]);

        $stats = $this->service->getCacheStats();
        self::assertSame(1, $stats['hits']);
        self::assertSame(1, $stats['misses']);
        self::assertSame(1, $stats['size']);
    }

    public function testCompileUsesCaching(): void
    {
        $expression = 'value * 2';

        // First compile
        $compiled1 = $this->service->compile($expression);

        // Second compile - should return same cached instance
        $compiled2 = $this->service->compile($expression);

        self::assertSame($compiled1, $compiled2);
    }

    public function testClearCacheRemovesAllCachedExpressions(): void
    {
        $this->service->evaluate('expr1', null);
        $this->service->evaluate('expr2', null);
        $this->service->evaluate('expr3', null);

        $stats = $this->service->getCacheStats();
        self::assertSame(3, $stats['size']);

        $this->service->clearCache();

        $stats = $this->service->getCacheStats();
        self::assertSame(0, $stats['size']);
    }

    public function testGetCacheReturnsCache(): void
    {
        $cache = $this->service->getCache();

        self::assertInstanceOf(ExpressionCacheInterface::class, $cache);
    }

    public function testMultipleEvaluationsOfSameExpression(): void
    {
        $expression = 'age > 18';

        // Multiple evaluations of the same expression
        for ($i = 0; $i < 5; ++$i) {
            $this->service->evaluate($expression, (object) ['age' => 20 + $i]);
        }

        $stats = $this->service->getCacheStats();
        self::assertSame(4, $stats['hits']); // First is miss, then 4 hits
        self::assertSame(1, $stats['misses']);
        self::assertSame(1, $stats['size']);
    }
}
