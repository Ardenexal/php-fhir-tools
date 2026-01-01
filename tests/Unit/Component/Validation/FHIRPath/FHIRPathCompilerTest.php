<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Validation\FHIRPath;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;
use Ardenexal\FHIRTools\Component\Validation\FHIRPath\FHIRPathCompiler;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Test FHIRPathCompiler functionality.
 *
 * @author FHIR Tools
 */
class FHIRPathCompilerTest extends TestCase
{
    private CacheItemPoolInterface $cache;
    private FHIRPathCompiler $compiler;

    protected function setUp(): void
    {
        $this->cache = $this->createMock(CacheItemPoolInterface::class);
        $this->compiler = new FHIRPathCompiler($this->cache);
    }

    public function testCompileSimpleExpression(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);
        $cacheItem->expects(self::once())->method('set');
        $cacheItem->expects(self::once())->method('expiresAfter');

        $this->cache->method('getItem')->willReturn($cacheItem);
        $this->cache->expects(self::once())->method('save');

        $ast = $this->compiler->compile(
            'name.family',
            'http://example.org/Profile',
            'Patient.name.family'
        );

        self::assertInstanceOf(ExpressionNode::class, $ast);
    }

    public function testCompileUsesCachedExpression(): void
    {
        $mockAst = $this->createMock(ExpressionNode::class);

        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(true);
        $cacheItem->method('get')->willReturn($mockAst);

        $this->cache->method('getItem')->willReturn($cacheItem);
        $this->cache->expects(self::never())->method('save');

        $ast = $this->compiler->compile(
            'name.family',
            'http://example.org/Profile',
            'Patient.name.family'
        );

        self::assertSame($mockAst, $ast);
    }

    public function testCompileUsesInMemoryCache(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);
        $this->cache->expects(self::once())->method('save');

        // First call
        $ast1 = $this->compiler->compile(
            'name.family',
            'http://example.org/Profile',
            'Patient.name.family'
        );

        // Second call should use in-memory cache
        $ast2 = $this->compiler->compile(
            'name.family',
            'http://example.org/Profile',
            'Patient.name.family'
        );

        self::assertSame($ast1, $ast2);
    }

    public function testEvaluateCompilesAndEvaluates(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);

        $resource = [
            'name' => [
                ['family' => 'Smith', 'given' => ['John']],
            ],
        ];

        $result = $this->compiler->evaluate(
            'name.family',
            $resource,
            'http://example.org/Profile',
            'Patient.name.family'
        );

        self::assertInstanceOf(Collection::class, $result);
        self::assertFalse($result->isEmpty());
    }

    public function testEvaluateWithExistsFunction(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);

        $resource = [
            'name' => [
                ['family' => 'Smith', 'given' => ['John']],
            ],
        ];

        $result = $this->compiler->evaluate(
            'name.family.exists()',
            $resource,
            'http://example.org/Profile',
            'Patient.name'
        );

        self::assertInstanceOf(Collection::class, $result);
        // exists() should return true (as boolean)
        $value = $result->first();
        self::assertTrue($value);
    }

    public function testEvaluateWithComplexExpression(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);

        $resource = [
            'name' => [
                ['family' => 'Smith', 'given' => ['John']],
                ['given' => ['Jane']], // No family name
            ],
        ];

        $result = $this->compiler->evaluate(
            'name.where(family.exists()).count() > 0',
            $resource,
            'http://example.org/Profile',
            'Patient.name'
        );

        self::assertInstanceOf(Collection::class, $result);
        $value = $result->first();
        self::assertTrue($value);
    }

    public function testClearCacheAll(): void
    {
        $this->cache->expects(self::once())->method('clear');

        $this->compiler->clearCache();
    }

    public function testClearCacheForSpecificProfile(): void
    {
        // First, populate in-memory cache
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);
        $this->cache->method('getItem')->willReturn($cacheItem);

        $this->compiler->compile('id', 'http://example.org/Profile1', 'Patient.id');
        $this->compiler->compile('name', 'http://example.org/Profile2', 'Patient.name');

        // Clear cache for specific profile
        $this->cache->expects(self::never())->method('clear');
        $this->compiler->clearCache('http://example.org/Profile1');

        // This is a basic test - actual cache clearing would need more verification
        self::assertTrue(true);
    }

    public function testGetEvaluator(): void
    {
        $evaluator = $this->compiler->getEvaluator();

        self::assertInstanceOf(\Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator::class, $evaluator);
    }

    public function testIsCachedReturnsTrueForCachedExpression(): void
    {
        $mockAst = $this->createMock(ExpressionNode::class);

        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(true);
        $cacheItem->method('get')->willReturn($mockAst);

        $this->cache->method('getItem')->willReturn($cacheItem);

        $isCached = $this->compiler->isCached(
            'name.family',
            'http://example.org/Profile',
            'Patient.name.family'
        );

        self::assertTrue($isCached);
    }

    public function testIsCachedReturnsFalseForUncachedExpression(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);

        $isCached = $this->compiler->isCached(
            'name.family',
            'http://example.org/Profile',
            'Patient.name.family'
        );

        self::assertFalse($isCached);
    }

    public function testIsCachedReturnsTrueForInMemoryCachedExpression(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);
        $this->cache->method('getItem')->willReturn($cacheItem);

        // Compile to populate in-memory cache
        $this->compiler->compile(
            'name.family',
            'http://example.org/Profile',
            'Patient.name.family'
        );

        // Check if cached (should hit in-memory cache)
        $isCached = $this->compiler->isCached(
            'name.family',
            'http://example.org/Profile',
            'Patient.name.family'
        );

        self::assertTrue($isCached);
    }
}
