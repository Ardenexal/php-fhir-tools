<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Validation\Terminology;

use Ardenexal\FHIRTools\Component\Validation\Terminology\CircuitBreaker;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @covers \Ardenexal\FHIRTools\Component\Validation\Terminology\CircuitBreaker
 */
final class CircuitBreakerTest extends TestCase
{
    private CacheItemPoolInterface $cache;

    protected function setUp(): void
    {
        $this->cache = $this->createMock(CacheItemPoolInterface::class);
    }

    public function testInitiallyAvailable(): void
    {
        $breaker = new CircuitBreaker($this->cache);

        $item = $this->createMock(CacheItemInterface::class);
        $item->method('isHit')->willReturn(false);

        $this->cache->method('getItem')->willReturn($item);

        self::assertTrue($breaker->isAvailable('test_service'));
    }

    public function testOpensAfterFailureThreshold(): void
    {
        $breaker = new CircuitBreaker($this->cache, failureThreshold: 3);

        $items = [];
        for ($i = 0; $i < 20; $i++) {
            $item = $this->createMock(CacheItemInterface::class);
            $item->method('set')->willReturnSelf();
            $item->method('expiresAfter')->willReturnSelf();
            $items[] = $item;
        }

        $callCount = 0;
        $this->cache->method('getItem')->willReturnCallback(function($key) use ($items, &$callCount) {
            return $items[$callCount++] ?? $items[0];
        });

        // Configure for first failure
        $items[0]->method('isHit')->willReturn(false); // state: not set = closed
        $items[1]->method('isHit')->willReturn(false); // failures: 0

        // Configure for second failure  
        $items[2]->method('isHit')->willReturn(false); // state: closed
        $items[3]->method('isHit')->willReturn(true); // failures: 1
        $items[3]->method('get')->willReturn(1);

        // Configure for third failure
        $items[4]->method('isHit')->willReturn(false); // state: closed
        $items[5]->method('isHit')->willReturn(true); // failures: 2
        $items[5]->method('get')->willReturn(2);

        // Configure for isAvailable check after opening
        $items[12]->method('isHit')->willReturn(true); // state: open
        $items[12]->method('get')->willReturn('open');
        $items[13]->method('isHit')->willReturn(false); // opened_at: not checked

        $this->cache->expects(self::atLeastOnce())->method('save');
        $this->cache->expects(self::once())->method('deleteItems');

        // Record 3 failures to reach threshold
        $breaker->recordFailure('test_service');
        $breaker->recordFailure('test_service');
        $breaker->recordFailure('test_service');

        // Circuit should now be OPEN
        self::assertFalse($breaker->isAvailable('test_service'));
    }

    public function testHalfOpenAfterTimeout(): void
    {
        $breaker = new CircuitBreaker($this->cache, timeout: 60);

        $stateItem = $this->createMock(CacheItemInterface::class);
        $stateItem->method('isHit')->willReturn(true);
        $stateItem->method('get')->willReturn('open');

        $openedAtItem = $this->createMock(CacheItemInterface::class);
        $openedAtItem->method('isHit')->willReturn(true);
        $openedAtItem->method('get')->willReturn(time() - 61); // 61 seconds ago

        $this->cache->method('getItem')->willReturnCallback(
            fn($key) => str_contains($key, 'opened_at') ? $openedAtItem : $stateItem
        );

        $this->cache->expects(self::once())->method('save');

        // Should transition to HALF_OPEN and allow request
        self::assertTrue($breaker->isAvailable('test_service'));
    }

    public function testClosesAfterSuccessThreshold(): void
    {
        $breaker = new CircuitBreaker($this->cache, successThreshold: 2);

        $items = [];
        for ($i = 0; $i < 10; $i++) {
            $item = $this->createMock(CacheItemInterface::class);
            $item->method('set')->willReturnSelf();
            $item->method('expiresAfter')->willReturnSelf();
            $items[] = $item;
        }

        $callCount = 0;
        $this->cache->method('getItem')->willReturnCallback(function($key) use ($items, &$callCount) {
            return $items[$callCount++] ?? $items[0];
        });

        // First recordSuccess call
        $items[0]->method('isHit')->willReturn(true); // state
        $items[0]->method('get')->willReturn('half_open');
        $items[1]->method('isHit')->willReturn(false); // successes: 0
        
        // Second recordSuccess call
        $items[4]->method('isHit')->willReturn(true); // state
        $items[4]->method('get')->willReturn('half_open');
        $items[5]->method('isHit')->willReturn(true); // successes: 1
        $items[5]->method('get')->willReturn(1);

        $this->cache->expects(self::atLeastOnce())->method('save');
        $this->cache->expects(self::once())->method('deleteItems');

        // Record 2 successes to reach threshold
        $breaker->recordSuccess('test_service');
        $breaker->recordSuccess('test_service');
    }

    public function testReopensOnFailureInHalfOpen(): void
    {
        $breaker = new CircuitBreaker($this->cache);

        $stateItem = $this->createMock(CacheItemInterface::class);
        $stateItem->method('isHit')->willReturn(true);
        $stateItem->method('get')->willReturn('half_open');

        $openedAtItem = $this->createMock(CacheItemInterface::class);

        $this->cache->method('getItem')->willReturnCallback(
            fn($key) => str_contains($key, 'opened_at') ? $openedAtItem : $stateItem
        );

        $this->cache->expects(self::atLeastOnce())->method('save');
        $this->cache->expects(self::once())->method('deleteItems');

        // Any failure in HALF_OPEN state reopens circuit
        $breaker->recordFailure('test_service');
    }

    public function testResetClearsState(): void
    {
        $breaker = new CircuitBreaker($this->cache);

        $this->cache->expects(self::atLeastOnce())->method('save');
        $this->cache->expects(self::once())->method('deleteItems');

        $breaker->reset('test_service');
    }
}
