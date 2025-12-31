<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Cache;

use Ardenexal\FHIRTools\Component\FHIRPath\Cache\InMemoryExpressionCache;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\LiteralNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\CompiledExpression;
use PHPUnit\Framework\TestCase;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;

/**
 * @covers \Ardenexal\FHIRTools\Component\FHIRPath\Cache\InMemoryExpressionCache
 *
 * @author Ardenexal
 */
class InMemoryExpressionCacheTest extends TestCase
{
    private InMemoryExpressionCache $cache;

    protected function setUp(): void
    {
        $this->cache = new InMemoryExpressionCache();
    }

    public function testHasReturnsFalseForNonexistentExpression(): void
    {
        self::assertFalse($this->cache->has('test'));
    }

    public function testGetReturnsNullForNonexistentExpression(): void
    {
        self::assertNull($this->cache->get('test'));
    }

    public function testSetAndGetExpression(): void
    {
        $expression = 'name.given';
        $compiled   = $this->createCompiledExpression($expression);

        $this->cache->set($expression, $compiled);

        self::assertTrue($this->cache->has($expression));
        self::assertSame($compiled, $this->cache->get($expression));
    }

    public function testDeleteRemovesExpression(): void
    {
        $expression = 'name.given';
        $compiled   = $this->createCompiledExpression($expression);

        $this->cache->set($expression, $compiled);
        self::assertTrue($this->cache->has($expression));

        $this->cache->delete($expression);
        self::assertFalse($this->cache->has($expression));
    }

    public function testClearRemovesAllExpressions(): void
    {
        $this->cache->set('expr1', $this->createCompiledExpression('expr1'));
        $this->cache->set('expr2', $this->createCompiledExpression('expr2'));
        $this->cache->set('expr3', $this->createCompiledExpression('expr3'));

        self::assertSame(3, $this->cache->getStats()['size']);

        $this->cache->clear();

        self::assertSame(0, $this->cache->getStats()['size']);
        self::assertFalse($this->cache->has('expr1'));
        self::assertFalse($this->cache->has('expr2'));
        self::assertFalse($this->cache->has('expr3'));
    }

    public function testStatsTrackHitsAndMisses(): void
    {
        $expression = 'name.given';
        $compiled   = $this->createCompiledExpression($expression);

        // Initial stats
        $stats = $this->cache->getStats();
        self::assertSame(0, $stats['hits']);
        self::assertSame(0, $stats['misses']);

        // Cache miss
        $this->cache->get('nonexistent');
        $stats = $this->cache->getStats();
        self::assertSame(0, $stats['hits']);
        self::assertSame(1, $stats['misses']);

        // Set and get (hit)
        $this->cache->set($expression, $compiled);
        $this->cache->get($expression);
        $stats = $this->cache->getStats();
        self::assertSame(1, $stats['hits']);
        self::assertSame(1, $stats['misses']);

        // Multiple hits
        $this->cache->get($expression);
        $this->cache->get($expression);
        $stats = $this->cache->getStats();
        self::assertSame(3, $stats['hits']);
        self::assertSame(1, $stats['misses']);
    }

    public function testStatsIncludeSize(): void
    {
        $stats = $this->cache->getStats();
        self::assertSame(0, $stats['size']);

        $this->cache->set('expr1', $this->createCompiledExpression('expr1'));
        $stats = $this->cache->getStats();
        self::assertSame(1, $stats['size']);

        $this->cache->set('expr2', $this->createCompiledExpression('expr2'));
        $stats = $this->cache->getStats();
        self::assertSame(2, $stats['size']);
    }

    public function testLRUEvictionWhenCacheFull(): void
    {
        $cache = new InMemoryExpressionCache(3); // Max 3 items

        // Fill cache
        $cache->set('expr1', $this->createCompiledExpression('expr1'));
        $cache->set('expr2', $this->createCompiledExpression('expr2'));
        $cache->set('expr3', $this->createCompiledExpression('expr3'));

        self::assertSame(3, $cache->getStats()['size']);

        // Access expr1 and expr3 to make expr2 least recently used
        $cache->get('expr1');
        $cache->get('expr3');

        // Add new expression should evict expr2
        $cache->set('expr4', $this->createCompiledExpression('expr4'));

        self::assertSame(3, $cache->getStats()['size']);
        self::assertTrue($cache->has('expr1'));
        self::assertFalse($cache->has('expr2')); // Evicted (LRU)
        self::assertTrue($cache->has('expr3'));
        self::assertTrue($cache->has('expr4'));
    }

    public function testGetHitRate(): void
    {
        $expression = 'name.given';
        $compiled   = $this->createCompiledExpression($expression);

        // No accesses
        self::assertSame(0.0, $this->cache->getHitRate());

        // 1 miss
        $this->cache->get('nonexistent');
        self::assertSame(0.0, $this->cache->getHitRate());

        // 1 hit, 1 miss = 50%
        $this->cache->set($expression, $compiled);
        $this->cache->get($expression);
        self::assertSame(50.0, $this->cache->getHitRate());

        // 3 hits, 1 miss = 75%
        $this->cache->get($expression);
        $this->cache->get($expression);
        self::assertSame(75.0, $this->cache->getHitRate());
    }

    public function testGetMaxSize(): void
    {
        $cache = new InMemoryExpressionCache(50);

        self::assertSame(50, $cache->getMaxSize());
    }

    public function testDefaultMaxSize(): void
    {
        $cache = new InMemoryExpressionCache();

        self::assertSame(100, $cache->getMaxSize());
    }

    public function testClearResetsStats(): void
    {
        $this->cache->set('expr1', $this->createCompiledExpression('expr1'));
        $this->cache->get('expr1');
        $this->cache->get('nonexistent');

        $stats = $this->cache->getStats();
        self::assertGreaterThan(0, $stats['hits']);
        self::assertGreaterThan(0, $stats['misses']);

        $this->cache->clear();

        $stats = $this->cache->getStats();
        self::assertSame(0, $stats['hits']);
        self::assertSame(0, $stats['misses']);
        self::assertSame(0, $stats['size']);
    }

    private function createCompiledExpression(string $expression): CompiledExpression
    {
        $ast       = new LiteralNode('test', TokenType::STRING, 1, 1);
        $evaluator = new FHIRPathEvaluator();

        return new CompiledExpression($ast, $evaluator, $expression);
    }
}
