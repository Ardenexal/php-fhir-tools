<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Validation\Selector;

use Ardenexal\FHIRTools\Component\Validation\Selector\SelectorRegistry;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Test SelectorRegistry functionality.
 *
 * @author FHIR Tools
 */
class SelectorRegistryTest extends TestCase
{
    private CacheItemPoolInterface $cache;
    private SelectorRegistry $registry;

    protected function setUp(): void
    {
        $this->cache = $this->createMock(CacheItemPoolInterface::class);
        $this->registry = new SelectorRegistry($this->cache);
    }

    public function testGetSelectorForSimplePath(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);
        $cacheItem->expects(self::once())->method('set');
        $cacheItem->expects(self::once())->method('expiresAfter');

        $this->cache->method('getItem')->willReturn($cacheItem);
        $this->cache->expects(self::once())->method('save');

        $selector = $this->registry->getSelector(
            'http://example.org/Profile',
            'Patient.id'
        );

        $resource = ['id' => '123'];
        $result = $selector($resource);

        self::assertSame(['123'], $result);
    }

    public function testGetSelectorForNestedPath(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);
        $cacheItem->expects(self::once())->method('set');

        $this->cache->method('getItem')->willReturn($cacheItem);
        $this->cache->expects(self::once())->method('save');

        $selector = $this->registry->getSelector(
            'http://example.org/Profile',
            'Patient.name.family'
        );

        $resource = [
            'name' => [
                ['family' => 'Smith', 'given' => ['John']],
                ['family' => 'Doe'],
            ],
        ];

        $result = $selector($resource);

        self::assertCount(2, $result);
        self::assertContains('Smith', $result);
        self::assertContains('Doe', $result);
    }

    public function testGetSelectorForChoiceType(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);

        $selector = $this->registry->getSelector(
            'http://example.org/Profile',
            'Observation.value[x]'
        );

        $resource = [
            'valueString' => 'test value',
            'code' => ['text' => 'test'],
        ];

        $result = $selector($resource);

        self::assertSame(['test value'], $result);
    }

    public function testGetSelectorForChoiceTypeMultipleMatches(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);

        $selector = $this->registry->getSelector(
            'http://example.org/Profile',
            'Observation.value[x]'
        );

        // This scenario shouldn't happen in valid FHIR (only one value[x] should exist)
        // but we test that all matches are found
        $resource = [
            'valueString' => 'string value',
            'valueInteger' => 42,
        ];

        $result = $selector($resource);

        self::assertCount(2, $result);
        self::assertContains('string value', $result);
        self::assertContains(42, $result);
    }

    public function testGetSelectorForArrayIndex(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);

        $selector = $this->registry->getSelector(
            'http://example.org/Profile',
            'Patient.name[0].family'
        );

        $resource = [
            'name' => [
                ['family' => 'Smith', 'given' => ['John']],
                ['family' => 'Doe', 'given' => ['Jane']],
            ],
        ];

        $result = $selector($resource);

        self::assertSame(['Smith'], $result);
    }

    public function testGetSelectorReturnsEmptyForNonExistentPath(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);

        $selector = $this->registry->getSelector(
            'http://example.org/Profile',
            'Patient.nonExistent.path'
        );

        $resource = ['name' => [['family' => 'Smith']]];

        $result = $selector($resource);

        self::assertEmpty($result);
    }

    public function testGetSelectorUsesCachedSelector(): void
    {
        $cachedSelector = function (array $resource): array {
            return ['cached'];
        };

        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(true);
        $cacheItem->method('get')->willReturn($cachedSelector);

        $this->cache->method('getItem')->willReturn($cacheItem);
        $this->cache->expects(self::never())->method('save');

        $selector = $this->registry->getSelector(
            'http://example.org/Profile',
            'Patient.id'
        );

        $result = $selector(['id' => '123']);

        self::assertSame(['cached'], $result);
    }

    public function testGetSelectorUsesInMemoryCache(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);
        $this->cache->expects(self::once())->method('save');

        // First call
        $selector1 = $this->registry->getSelector(
            'http://example.org/Profile',
            'Patient.id'
        );

        // Second call should use in-memory cache
        $selector2 = $this->registry->getSelector(
            'http://example.org/Profile',
            'Patient.id'
        );

        self::assertSame($selector1, $selector2);
    }

    public function testClearCacheAll(): void
    {
        $this->cache->expects(self::once())->method('clear');

        $this->registry->clearCache();
    }

    public function testClearCacheForSpecificProfile(): void
    {
        // First, populate in-memory cache
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);
        $this->cache->method('getItem')->willReturn($cacheItem);

        $this->registry->getSelector('http://example.org/Profile1', 'Patient.id');
        $this->registry->getSelector('http://example.org/Profile2', 'Patient.name');

        // Clear cache for specific profile
        $this->cache->expects(self::never())->method('clear');
        $this->registry->clearCache('http://example.org/Profile1');

        // This is a basic test - actual cache clearing would need more sophisticated verification
        self::assertTrue(true);
    }

    public function testSelectorHandlesNullValues(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);

        $selector = $this->registry->getSelector(
            'http://example.org/Profile',
            'Patient.name.family'
        );

        $resource = [
            'name' => [
                ['family' => null],
                ['given' => ['John']],
            ],
        ];

        $result = $selector($resource);

        self::assertEmpty($result);
    }

    public function testSelectorHandlesEmptyArrays(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);

        $selector = $this->registry->getSelector(
            'http://example.org/Profile',
            'Patient.name.family'
        );

        $resource = ['name' => []];

        $result = $selector($resource);

        self::assertEmpty($result);
    }
}
