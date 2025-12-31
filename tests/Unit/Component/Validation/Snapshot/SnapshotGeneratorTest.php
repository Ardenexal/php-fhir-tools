<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Validation\Snapshot;

use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoaderInterface;
use Ardenexal\FHIRTools\Component\Validation\Exception\SnapshotGenerationException;
use Ardenexal\FHIRTools\Component\Validation\Snapshot\SnapshotGenerator;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Test SnapshotGenerator functionality.
 *
 * @author FHIR Tools
 */
class SnapshotGeneratorTest extends TestCase
{
    private PackageLoaderInterface $packageLoader;
    private CacheItemPoolInterface $cache;
    private SnapshotGenerator $generator;

    protected function setUp(): void
    {
        $this->packageLoader = $this->createMock(PackageLoaderInterface::class);
        $this->cache = $this->createMock(CacheItemPoolInterface::class);
        $this->generator = new SnapshotGenerator($this->packageLoader, $this->cache);
    }

    public function testGenerateThrowsExceptionWhenUrlMissing(): void
    {
        $this->expectException(SnapshotGenerationException::class);
        $this->expectExceptionMessage('StructureDefinition must have a url');

        $sd = ['type' => 'Patient'];

        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($cacheItem);

        $this->generator->generate($sd);
    }

    public function testGenerateReturnsSnapshotForBaseResource(): void
    {
        $sd = [
            'url' => 'http://example.org/StructureDefinition/CustomBase',
            'type' => 'CustomBase',
            'differential' => [
                'element' => [
                    ['path' => 'CustomBase', 'min' => 0, 'max' => '*'],
                    ['path' => 'CustomBase.id', 'min' => 0, 'max' => '1'],
                ],
            ],
        ];

        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);
        $cacheItem->expects(self::once())->method('set');
        $cacheItem->expects(self::once())->method('expiresAfter');

        $this->cache->method('getItem')->willReturn($cacheItem);
        $this->cache->expects(self::once())->method('save');

        $result = $this->generator->generate($sd);

        self::assertArrayHasKey('snapshot', $result);
        self::assertArrayHasKey('element', $result['snapshot']);
        self::assertCount(2, $result['snapshot']['element']);
    }

    public function testGenerateReturnsCachedSnapshot(): void
    {
        $sd = [
            'url' => 'http://example.org/StructureDefinition/Test',
            'version' => '1.0.0',
            'type' => 'Test',
        ];

        $cachedSd = array_merge($sd, [
            'snapshot' => [
                'element' => [
                    ['path' => 'Test', 'min' => 0, 'max' => '*'],
                ],
            ],
        ]);

        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(true);
        $cacheItem->method('get')->willReturn($cachedSd);

        $this->cache->method('getItem')->willReturn($cacheItem);
        $this->cache->expects(self::never())->method('save');

        $result = $this->generator->generate($sd);

        self::assertArrayHasKey('snapshot', $result);
        self::assertSame($cachedSd, $result);
    }
}
