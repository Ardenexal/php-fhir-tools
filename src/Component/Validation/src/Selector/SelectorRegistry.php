<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Selector;

use Psr\Cache\CacheItemPoolInterface;

/**
 * Registry for precomputed path selectors that navigate FHIR resources.
 *
 * Path selectors efficiently extract values from FHIR resources for validation.
 * They handle:
 * - Nested element paths (e.g., "Patient.name.family")
 * - Array navigation (e.g., "Patient.name[0].given")
 * - Choice types (e.g., "value[x]" matches "valueString", "valueInteger", etc.)
 * - Complex paths with multiple levels
 *
 * Selectors are precomputed and cached per profile for performance.
 *
 * @author FHIR Tools
 */
class SelectorRegistry
{
    private const CACHE_KEY_PREFIX = 'selector:';

    private const CACHE_TTL = 86400; // 24 hours

    /**
     * @var array<string, callable> In-memory cache of compiled selectors
     */
    private array $selectors = [];

    public function __construct(
        private readonly CacheItemPoolInterface $cache
    ) {
    }

    /**
     * Get or create a selector for an element path.
     *
     * @param string $profileUrl  The profile URL
     * @param string $elementPath The element path (e.g., "Patient.name.family")
     *
     * @return callable Selector function that takes a resource and returns matching values
     */
    public function getSelector(string $profileUrl, string $elementPath): callable
    {
        $cacheKey = $this->getCacheKey($profileUrl, $elementPath);

        // Check in-memory cache first
        if (isset($this->selectors[$cacheKey])) {
            return $this->selectors[$cacheKey];
        }

        // Check PSR-6 cache
        $cachedItem = $this->cache->getItem($cacheKey);
        if ($cachedItem->isHit()) {
            $selector                   = $cachedItem->get();
            $this->selectors[$cacheKey] = $selector;

            return $selector;
        }

        // Create new selector
        $selector = $this->createSelector($elementPath);

        // Cache it
        $this->selectors[$cacheKey] = $selector;
        $cachedItem->set($selector);
        $cachedItem->expiresAfter(self::CACHE_TTL);
        $this->cache->save($cachedItem);

        return $selector;
    }

    /**
     * Create a selector function for an element path.
     *
     * @param string $elementPath The element path
     *
     * @return callable Selector function
     */
    private function createSelector(string $elementPath): callable
    {
        // Parse the path into segments
        $segments = $this->parsePath($elementPath);

        return function(array $resource) use ($segments): array {
            return $this->selectValues($resource, $segments);
        };
    }

    /**
     * Parse an element path into segments.
     *
     * @param string $path The element path
     *
     * @return array<string> Path segments
     */
    private function parsePath(string $path): array
    {
        // Split by dots, but handle array indices
        $segments = explode('.', $path);

        // Remove the resource type (first segment) as it's not part of the actual resource structure
        // e.g., "Patient.name.family" becomes ["name", "family"]
        if (count($segments) > 1) {
            array_shift($segments);
        }

        // Clean up segments
        return array_map('trim', $segments);
    }

    /**
     * Select values from a resource using path segments.
     *
     * @param mixed         $current  Current value being traversed
     * @param array<string> $segments Remaining path segments
     *
     * @return array<mixed> Selected values
     */
    private function selectValues(mixed $current, array $segments): array
    {
        // Base case: no more segments
        if (empty($segments)) {
            return $current !== null ? [$current] : [];
        }

        // If current is null or not traversable, return empty
        if ($current === null || (!is_array($current) && !is_object($current))) {
            return [];
        }

        $segment = array_shift($segments);

        // Handle choice types (e.g., value[x])
        if (str_ends_with($segment, '[x]')) {
            return $this->selectChoiceType($current, $segment, $segments);
        }

        // Handle array access (e.g., name[0])
        if (preg_match('/^(.+)\[(\d+)\]$/', $segment, $matches)) {
            return $this->selectArrayIndex($current, $matches[1], (int) $matches[2], $segments);
        }

        // Handle regular property access
        return $this->selectProperty($current, $segment, $segments);
    }

    /**
     * Select choice type values (e.g., value[x] matches valueString, valueInteger, etc.).
     *
     * @param mixed         $current  Current value
     * @param string        $segment  Choice type segment (e.g., "value[x]")
     * @param array<string> $segments Remaining segments
     *
     * @return array<mixed> Selected values
     */
    private function selectChoiceType(mixed $current, string $segment, array $segments): array
    {
        if (!is_array($current)) {
            return [];
        }

        // Remove [x] suffix to get base name
        $baseName = substr($segment, 0, -3);

        $results = [];

        // Look for all properties starting with baseName
        foreach ($current as $key => $value) {
            if (is_string($key) && str_starts_with($key, $baseName) && $key !== $baseName) {
                // This is a choice type match (e.g., valueString for value[x])
                $matched = $this->selectValues($value, $segments);
                $results = array_merge($results, $matched);
            }
        }

        return $results;
    }

    /**
     * Select array element by index.
     *
     * @param mixed         $current  Current value
     * @param string        $property Property name
     * @param int           $index    Array index
     * @param array<string> $segments Remaining segments
     *
     * @return array<mixed> Selected values
     */
    private function selectArrayIndex(mixed $current, string $property, int $index, array $segments): array
    {
        if (!is_array($current)) {
            return [];
        }

        // Get the array property
        $array = $current[$property] ?? null;

        if (!is_array($array)) {
            return [];
        }

        // Get the specific index
        $value = $array[$index] ?? null;

        if ($value === null) {
            return [];
        }

        return $this->selectValues($value, $segments);
    }

    /**
     * Select property value.
     *
     * @param mixed         $current  Current value
     * @param string        $property Property name
     * @param array<string> $segments Remaining segments
     *
     * @return array<mixed> Selected values
     */
    private function selectProperty(mixed $current, string $property, array $segments): array
    {
        if (!is_array($current)) {
            return [];
        }

        $value = $current[$property] ?? null;

        // If value is null, return empty
        if ($value === null) {
            return [];
        }

        // If value is an array of objects, iterate over them
        if (is_array($value) && !empty($value)) {
            // Check if it's an array of resources (associative arrays)
            $first = reset($value);
            if (is_array($first) && !isset($first[0])) {
                // Array of objects - recurse into each
                $results = [];
                foreach ($value as $item) {
                    $matched = $this->selectValues($item, $segments);
                    $results = array_merge($results, $matched);
                }

                return $results;
            }
        }

        // Single value or simple array
        return $this->selectValues($value, $segments);
    }

    /**
     * Get cache key for a selector.
     */
    private function getCacheKey(string $profileUrl, string $elementPath): string
    {
        return self::CACHE_KEY_PREFIX . md5($profileUrl . '|' . $elementPath);
    }

    /**
     * Clear cached selectors.
     *
     * @param string|null $profileUrl Optional profile URL to clear specific profile
     */
    public function clearCache(?string $profileUrl = null): void
    {
        if ($profileUrl === null) {
            // Clear all in-memory cache
            $this->selectors = [];
            $this->cache->clear();
        } else {
            // Clear specific profile's selectors
            foreach (array_keys($this->selectors) as $key) {
                if (str_contains($key, md5($profileUrl))) {
                    unset($this->selectors[$key]);
                }
            }
        }
    }
}
