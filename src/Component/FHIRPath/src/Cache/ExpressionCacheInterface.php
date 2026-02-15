<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Cache;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\CompiledExpression;

/**
 * Interface for FHIRPath expression caching.
 *
 * Provides a contract for caching compiled FHIRPath expressions to improve
 * performance by avoiding re-parsing of frequently used expressions.
 *
 * @author Ardenexal
 */
interface ExpressionCacheInterface
{
    /**
     * Check if an expression is cached.
     *
     * @param string $expression The expression string
     *
     * @return bool True if the expression is cached
     */
    public function has(string $expression): bool;

    /**
     * Get a cached compiled expression.
     *
     * @param string $expression The expression string
     *
     * @return CompiledExpression|null The cached expression or null if not found
     */
    public function get(string $expression): ?CompiledExpression;

    /**
     * Store a compiled expression in the cache.
     *
     * @param string             $expression The expression string
     * @param CompiledExpression $compiled   The compiled expression
     */
    public function set(string $expression, CompiledExpression $compiled): void;

    /**
     * Remove an expression from the cache.
     *
     * @param string $expression The expression string
     */
    public function delete(string $expression): void;

    /**
     * Clear all cached expressions.
     */
    public function clear(): void;

    /**
     * Get cache statistics.
     *
     * @return array{hits: int, misses: int, size: int} Cache statistics
     */
    public function getStats(): array;
}
