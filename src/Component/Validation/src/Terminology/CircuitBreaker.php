<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Terminology;

use Psr\Cache\CacheItemPoolInterface;

/**
 * Circuit breaker for terminology server failures.
 *
 * Implements the circuit breaker pattern to prevent cascading failures
 * when the remote terminology server is unavailable.
 *
 * States:
 * - CLOSED: Normal operation, requests allowed
 * - OPEN: Too many failures, requests blocked
 * - HALF_OPEN: Testing if service recovered
 *
 * @author Alex Murray <alex@ardenexal.com>
 */
final class CircuitBreaker
{
    private const STATE_CLOSED = 'closed';
    private const STATE_OPEN = 'open';
    private const STATE_HALF_OPEN = 'half_open';

    private const CACHE_KEY_PREFIX = 'circuit_breaker_';

    public function __construct(
        private readonly CacheItemPoolInterface $cache,
        private readonly int $failureThreshold = 5,
        private readonly int $successThreshold = 2,
        private readonly int $timeout = 60,
    ) {
    }

    /**
     * Check if requests are allowed through the circuit breaker.
     */
    public function isAvailable(string $serviceId): bool
    {
        $state = $this->getState($serviceId);

        if ($state === self::STATE_CLOSED) {
            return true;
        }

        if ($state === self::STATE_OPEN) {
            // Check if timeout expired
            $item = $this->cache->getItem($this->getCacheKey($serviceId, 'opened_at'));
            if ($item->isHit()) {
                $openedAt = $item->get();
                if (time() - $openedAt >= $this->timeout) {
                    $this->setState($serviceId, self::STATE_HALF_OPEN);
                    return true;
                }
            }
            return false;
        }

        // HALF_OPEN state allows limited requests
        return true;
    }

    /**
     * Record a successful request.
     */
    public function recordSuccess(string $serviceId): void
    {
        $state = $this->getState($serviceId);

        if ($state === self::STATE_HALF_OPEN) {
            $successes = $this->incrementCounter($serviceId, 'successes');
            if ($successes >= $this->successThreshold) {
                $this->setState($serviceId, self::STATE_CLOSED);
                $this->resetCounters($serviceId);
            }
        } elseif ($state === self::STATE_CLOSED) {
            // Reset failure counter on success
            $this->resetCounter($serviceId, 'failures');
        }
    }

    /**
     * Record a failed request.
     */
    public function recordFailure(string $serviceId): void
    {
        $state = $this->getState($serviceId);

        if ($state === self::STATE_HALF_OPEN) {
            // Any failure in half-open state reopens circuit
            $this->openCircuit($serviceId);
        } elseif ($state === self::STATE_CLOSED) {
            $failures = $this->incrementCounter($serviceId, 'failures');
            if ($failures >= $this->failureThreshold) {
                $this->openCircuit($serviceId);
            }
        }
    }

    /**
     * Manually reset the circuit breaker.
     */
    public function reset(string $serviceId): void
    {
        $this->setState($serviceId, self::STATE_CLOSED);
        $this->resetCounters($serviceId);
    }

    /**
     * Get current state of circuit breaker.
     */
    private function getState(string $serviceId): string
    {
        $item = $this->cache->getItem($this->getCacheKey($serviceId, 'state'));
        return $item->isHit() ? $item->get() : self::STATE_CLOSED;
    }

    /**
     * Set state of circuit breaker.
     */
    private function setState(string $serviceId, string $state): void
    {
        $item = $this->cache->getItem($this->getCacheKey($serviceId, 'state'));
        $item->set($state);
        $item->expiresAfter(3600); // 1 hour
        $this->cache->save($item);
    }

    /**
     * Open the circuit (block requests).
     */
    private function openCircuit(string $serviceId): void
    {
        $this->setState($serviceId, self::STATE_OPEN);
        
        $item = $this->cache->getItem($this->getCacheKey($serviceId, 'opened_at'));
        $item->set(time());
        $item->expiresAfter(3600);
        $this->cache->save($item);
        
        $this->resetCounters($serviceId);
    }

    /**
     * Increment a counter.
     */
    private function incrementCounter(string $serviceId, string $counterName): int
    {
        $item = $this->cache->getItem($this->getCacheKey($serviceId, $counterName));
        $count = $item->isHit() ? $item->get() : 0;
        $count++;
        
        $item->set($count);
        $item->expiresAfter(3600);
        $this->cache->save($item);
        
        return $count;
    }

    /**
     * Reset a specific counter.
     */
    private function resetCounter(string $serviceId, string $counterName): void
    {
        $this->cache->deleteItem($this->getCacheKey($serviceId, $counterName));
    }

    /**
     * Reset all counters for a service.
     */
    private function resetCounters(string $serviceId): void
    {
        $this->cache->deleteItems([
            $this->getCacheKey($serviceId, 'failures'),
            $this->getCacheKey($serviceId, 'successes'),
            $this->getCacheKey($serviceId, 'opened_at'),
        ]);
    }

    /**
     * Generate cache key.
     */
    private function getCacheKey(string $serviceId, string $suffix): string
    {
        return self::CACHE_KEY_PREFIX . md5($serviceId) . '_' . $suffix;
    }
}
