<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Cache;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\CompiledExpression;

/**
 * In-memory cache for compiled FHIRPath expressions.
 *
 * Provides a simple, fast in-memory cache with LRU (Least Recently Used) eviction
 * when the cache reaches its maximum size. Tracks cache hits and misses for monitoring.
 *
 * @author Ardenexal
 */
class InMemoryExpressionCache implements ExpressionCacheInterface
{
    /**
     * @var array<string, CompiledExpression>
     */
    private array $cache = [];

    /**
     * @var array<string, int>
     */
    private array $accessTimes = [];

    private int $hits = 0;

    private int $misses = 0;

    private int $accessCounter = 0;

    /**
     * Create a new in-memory cache.
     *
     * @param int $maxSize Maximum number of expressions to cache (default: 100)
     */
    public function __construct(
        private readonly int $maxSize = 100
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $expression): bool
    {
        return isset($this->cache[$expression]);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $expression): ?CompiledExpression
    {
        if (! isset($this->cache[$expression])) {
            ++$this->misses;

            return null;
        }

        ++$this->hits;
        $this->accessTimes[$expression] = ++$this->accessCounter;

        return $this->cache[$expression];
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $expression, CompiledExpression $compiled): void
    {
        // If cache is full, remove least recently used entry
        if (count($this->cache) >= $this->maxSize && ! isset($this->cache[$expression])) {
            $this->evictLeastRecentlyUsed();
        }

        $this->cache[$expression]       = $compiled;
        $this->accessTimes[$expression] = ++$this->accessCounter;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $expression): void
    {
        unset($this->cache[$expression], $this->accessTimes[$expression]);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->cache         = [];
        $this->accessTimes   = [];
        $this->hits          = 0;
        $this->misses        = 0;
        $this->accessCounter = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getStats(): array
    {
        return [
            'hits'   => $this->hits,
            'misses' => $this->misses,
            'size'   => count($this->cache),
        ];
    }

    /**
     * Get the cache hit rate as a percentage.
     *
     * @return float Hit rate (0-100)
     */
    public function getHitRate(): float
    {
        $total = $this->hits + $this->misses;

        if ($total === 0) {
            return 0.0;
        }

        return ($this->hits / $total) * 100;
    }

    /**
     * Get the maximum cache size.
     *
     * @return int Maximum number of cached expressions
     */
    public function getMaxSize(): int
    {
        return $this->maxSize;
    }

    /**
     * Evict the least recently used entry from the cache.
     */
    private function evictLeastRecentlyUsed(): void
    {
        if (empty($this->accessTimes)) {
            return;
        }

        $lruExpression = array_key_first($this->accessTimes);
        $lruTime       = PHP_INT_MAX;

        foreach ($this->accessTimes as $expression => $time) {
            if ($time < $lruTime) {
                $lruTime       = $time;
                $lruExpression = $expression;
            }
        }

        $this->delete($lruExpression);
    }
}
