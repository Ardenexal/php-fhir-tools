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
     * Compiled expressions in recency order: the least recently used entry is first.
     *
     * PHP arrays keep insertion order, so moving an entry to the end on every hit and evicting the
     * first key gives LRU in constant time, with no separate access-time map to scan.
     *
     * @var array<string, CompiledExpression>
     */
    private array $cache = [];

    private int $hits = 0;

    private int $misses = 0;

    /**
     * Create a new in-memory cache.
     *
     * The default holds the conformance invariants of a whole FHIR release with room to spare.
     * Validation evaluates them in a repeating cycle, one resource type after another, and an LRU
     * cache smaller than that cycle misses on every expression: it re-parsed each one.
     *
     * @param int $maxSize Maximum number of expressions to cache (default: 1000)
     */
    public function __construct(
        private readonly int $maxSize = 1000
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

        // Move the entry to the end, the most recently used position.
        $compiled = $this->cache[$expression];
        unset($this->cache[$expression]);
        $this->cache[$expression] = $compiled;

        return $compiled;
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

        // Re-setting an entry also makes it the most recently used.
        unset($this->cache[$expression]);
        $this->cache[$expression] = $compiled;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $expression): void
    {
        unset($this->cache[$expression]);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->cache  = [];
        $this->hits   = 0;
        $this->misses = 0;
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
        $lruExpression = array_key_first($this->cache);

        // An expression such as `1` is held under an integer key, because PHP converts numeric
        // string keys, so the key is turned back into the string it was stored under.
        if ($lruExpression !== null) {
            $this->delete((string) $lruExpression);
        }
    }
}
