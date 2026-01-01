<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Slicing;

use Psr\Cache\CacheItemPoolInterface;

/**
 * Slicer for discriminator-based slice matching with cardinality enforcement.
 *
 * Implements FHIR slicing rules:
 * - Discriminator types: value, pattern, type, profile, exists
 * - Slice ordering: ordered or unordered
 * - Slice cardinality: min..max per slice
 * - Slicing rules: closed, open, openAtEnd
 *
 * @author Alex Murray <alex@ardenexal.com>
 */
final class Slicer
{
    /**
     * In-memory cache for compiled slice matchers within a request.
     *
     * @var array<string, callable>
     */
    private array $memoryCache = [];

    public function __construct(
        private readonly CacheItemPoolInterface $cache,
    ) {
    }

    /**
     * Match array elements to slices using discriminators.
     *
     * Returns array of matched slices with their elements:
     * [
     *     'sliceName1' => [element1, element3],
     *     'sliceName2' => [element2],
     *     '__unmatched' => [element4]
     * ]
     *
     * @param array<int, mixed>    $elements          Array elements to slice
     * @param array<string, mixed> $slicingDefinition Slicing definition from StructureDefinition snapshot
     * @param string               $profileUrl        Profile URL for cache key scoping
     * @param string               $elementPath       Element path for cache key scoping
     *
     * @return array<string, array<int, mixed>> Bucketed elements by slice name
     */
    public function match(
        array $elements,
        array $slicingDefinition,
        string $profileUrl,
        string $elementPath
    ): array {
        if (empty($elements)) {
            return [];
        }

        $slices = $slicingDefinition['slices'] ?? [];
        if (empty($slices)) {
            return ['__unmatched' => $elements];
        }

        $discriminators = $slicingDefinition['discriminator'] ?? [];
        $rules          = $slicingDefinition['rules']         ?? 'open'; // open, closed, openAtEnd

        // Build slice matchers (cached)
        $matchers = [];
        foreach ($slices as $sliceName => $sliceDefinition) {
            $matchers[$sliceName] = $this->getSliceMatcher(
                $discriminators,
                $sliceDefinition,
                $profileUrl,
                $elementPath,
                $sliceName,
            );
        }

        // Bucket elements by slice
        $buckets                = array_fill_keys(array_keys($slices), []);
        $buckets['__unmatched'] = [];

        foreach ($elements as $index => $element) {
            $matched = false;

            // Try each slice in order
            foreach ($matchers as $sliceName => $matcher) {
                if ($matcher($element)) {
                    $buckets[$sliceName][] = $element;
                    $matched               = true;
                    break; // First match wins
                }
            }

            if (!$matched) {
                $buckets['__unmatched'][] = $element;
            }
        }

        // Remove empty buckets (except __unmatched if needed for closed slicing)
        foreach ($buckets as $sliceName => $bucket) {
            if (empty($bucket) && $sliceName !== '__unmatched') {
                unset($buckets[$sliceName]);
            }
        }

        // For closed slicing, keep __unmatched to detect violations
        // For open slicing, remove __unmatched if empty
        if ($rules === 'open' && empty($buckets['__unmatched'])) {
            unset($buckets['__unmatched']);
        }

        return $buckets;
    }

    /**
     * Validate slice cardinality constraints.
     *
     * Returns validation issues for slices that violate min/max cardinality.
     *
     * @param array<string, array<int, mixed>> $buckets           Bucketed elements from match()
     * @param array<string, mixed>             $slicingDefinition Slicing definition with cardinality
     *
     * @return array<int, array{slice: string, violation: string, min?: int, max?: int|string, actual: int}> Validation issues
     */
    public function validateCardinality(
        array $buckets,
        array $slicingDefinition
    ): array {
        $slices = $slicingDefinition['slices'] ?? [];
        $rules  = $slicingDefinition['rules']  ?? 'open';
        $issues = [];

        foreach ($slices as $sliceName => $sliceDefinition) {
            $count = count($buckets[$sliceName] ?? []);
            $min   = $sliceDefinition['min'] ?? 0;
            $max   = $sliceDefinition['max'] ?? '*';

            // Check minimum cardinality
            if ($count < $min) {
                $issues[] = [
                    'slice'     => $sliceName,
                    'violation' => 'min_cardinality',
                    'min'       => $min,
                    'actual'    => $count,
                ];
            }

            // Check maximum cardinality
            if ($max !== '*' && $count > (int) $max) {
                $issues[] = [
                    'slice'     => $sliceName,
                    'violation' => 'max_cardinality',
                    'max'       => $max,
                    'actual'    => $count,
                ];
            }
        }

        // Check for unmatched elements in closed slicing
        if ($rules === 'closed' && !empty($buckets['__unmatched'])) {
            $issues[] = [
                'slice'     => '__unmatched',
                'violation' => 'closed_slicing',
                'actual'    => count($buckets['__unmatched']),
            ];
        }

        return $issues;
    }

    /**
     * Get or compile a slice matcher function.
     *
     * Matcher function takes an element and returns true if it matches the slice.
     *
     * @param array<int, array{type: string, path: string}> $discriminators  Discriminator definitions
     * @param array<string, mixed>                          $sliceDefinition Slice definition with discriminator values
     * @param string                                        $profileUrl      Profile URL for cache key scoping
     * @param string                                        $elementPath     Element path for cache key scoping
     * @param string                                        $sliceName       Slice name for cache key scoping
     *
     * @return callable Matcher function
     */
    private function getSliceMatcher(
        array $discriminators,
        array $sliceDefinition,
        string $profileUrl,
        string $elementPath,
        string $sliceName
    ): callable {
        $cacheKey = $this->buildCacheKey($profileUrl, $elementPath, $sliceName);

        // Check memory cache
        if (isset($this->memoryCache[$cacheKey])) {
            return $this->memoryCache[$cacheKey];
        }

        // Check PSR-6 cache
        $cacheItem = $this->cache->getItem($cacheKey);
        if ($cacheItem->isHit()) {
            $matcher                      = $cacheItem->get();
            $this->memoryCache[$cacheKey] = $matcher;

            return $matcher;
        }

        // Compile matcher
        $matcher = $this->compileSliceMatcher($discriminators, $sliceDefinition);

        // Cache compiled matcher
        $cacheItem->set($matcher);
        $cacheItem->expiresAfter(86400); // 24 hours
        $this->cache->save($cacheItem);

        $this->memoryCache[$cacheKey] = $matcher;

        return $matcher;
    }

    /**
     * Compile a slice matcher function from discriminators and slice definition.
     *
     * @param array<int, array{type: string, path: string}> $discriminators  Discriminator definitions
     * @param array<string, mixed>                          $sliceDefinition Slice definition with discriminator values
     *
     * @return callable Matcher function
     */
    private function compileSliceMatcher(
        array $discriminators,
        array $sliceDefinition
    ): callable {
        // Build matchers for each discriminator
        $matchers = [];

        foreach ($discriminators as $discriminator) {
            $type = $discriminator['type'];
            $path = $discriminator['path'];

            $matcher = match ($type) {
                'value'   => $this->compileValueMatcher($path, $sliceDefinition),
                'pattern' => $this->compilePatternMatcher($path, $sliceDefinition),
                'type'    => $this->compileTypeMatcher($path, $sliceDefinition),
                'profile' => $this->compileProfileMatcher($path, $sliceDefinition),
                'exists'  => $this->compileExistsMatcher($path, $sliceDefinition),
                default   => fn () => false, // Unknown discriminator type
            };

            $matchers[] = $matcher;
        }

        // Combine matchers with AND logic (all must match)
        return function($element) use ($matchers): bool {
            foreach ($matchers as $matcher) {
                if (!$matcher($element)) {
                    return false;
                }
            }

            return true;
        };
    }

    /**
     * Compile a value discriminator matcher.
     *
     * Matches element if path resolves to expected value.
     */
    private function compileValueMatcher(string $path, array $sliceDefinition): callable
    {
        $expectedValue = $sliceDefinition['discriminatorValue'][$path] ?? null;

        return function($element) use ($path, $expectedValue): bool {
            $actualValue = $this->resolvePath($element, $path);

            return $actualValue === $expectedValue;
        };
    }

    /**
     * Compile a pattern discriminator matcher.
     *
     * Matches element if path resolves to value matching pattern.
     */
    private function compilePatternMatcher(string $path, array $sliceDefinition): callable
    {
        $pattern = $sliceDefinition['discriminatorPattern'][$path] ?? null;

        return function($element) use ($path, $pattern): bool {
            if ($pattern === null) {
                return false;
            }

            $actualValue = $this->resolvePath($element, $path);

            // For complex patterns, check if all pattern properties match
            if (is_array($pattern) && is_array($actualValue)) {
                foreach ($pattern as $key => $value) {
                    if (!isset($actualValue[$key]) || $actualValue[$key] !== $value) {
                        return false;
                    }
                }

                return true;
            }

            return $actualValue === $pattern;
        };
    }

    /**
     * Compile a type discriminator matcher.
     *
     * Matches element if path resolves to value of expected type.
     */
    private function compileTypeMatcher(string $path, array $sliceDefinition): callable
    {
        $expectedType = $sliceDefinition['discriminatorType'][$path] ?? null;

        return function($element) use ($path, $expectedType): bool {
            if ($expectedType === null) {
                return false;
            }

            $actualValue = $this->resolvePath($element, $path);

            // Check resourceType for resources
            if (is_array($actualValue) && isset($actualValue['resourceType'])) {
                return $actualValue['resourceType'] === $expectedType;
            }

            // Check type suffix for choice types (e.g., valueString, valueInteger)
            if (is_string($path) && str_contains($path, '[x]')) {
                $basePath  = str_replace('[x]', '', $path);
                $typedPath = $basePath . ucfirst($expectedType);

                return $this->resolvePath($element, $typedPath) !== null;
            }

            return false;
        };
    }

    /**
     * Compile a profile discriminator matcher.
     *
     * Matches element if meta.profile[] contains expected profile.
     */
    private function compileProfileMatcher(string $path, array $sliceDefinition): callable
    {
        $expectedProfile = $sliceDefinition['discriminatorProfile'][$path] ?? null;

        return function($element) use ($path, $expectedProfile): bool {
            if ($expectedProfile === null) {
                return false;
            }

            $actualValue = $this->resolvePath($element, $path);

            // Check meta.profile array
            if (is_array($actualValue) && isset($actualValue['meta']['profile'])) {
                $profiles = $actualValue['meta']['profile'];

                return in_array($expectedProfile, $profiles, true);
            }

            return false;
        };
    }

    /**
     * Compile an exists discriminator matcher.
     *
     * Matches element if path exists (or doesn't exist).
     */
    private function compileExistsMatcher(string $path, array $sliceDefinition): callable
    {
        $shouldExist = $sliceDefinition['discriminatorExists'][$path] ?? true;

        return function($element) use ($path, $shouldExist): bool {
            $actualValue = $this->resolvePath($element, $path);
            $exists      = $actualValue !== null;

            return $exists === $shouldExist;
        };
    }

    /**
     * Resolve a path within an element.
     *
     * Supports nested paths like "coding.system" and array navigation.
     *
     * @param mixed  $element Element to navigate
     * @param string $path    Dot-separated path
     *
     * @return mixed Resolved value or null
     */
    private function resolvePath(mixed $element, string $path): mixed
    {
        if (!is_array($element)) {
            return null;
        }

        $parts   = explode('.', $path);
        $current = $element;

        foreach ($parts as $part) {
            if (!is_array($current) || !isset($current[$part])) {
                return null;
            }
            $current = $current[$part];
        }

        return $current;
    }

    /**
     * Build cache key for slice matcher.
     */
    private function buildCacheKey(
        string $profileUrl,
        string $elementPath,
        string $sliceName
    ): string {
        return sprintf(
            'fhir_slicer_slice:%s|%s|%s',
            md5($profileUrl),
            md5($elementPath),
            md5($sliceName),
        );
    }

    /**
     * Clear all cached slice matchers.
     */
    public function clearCache(?string $profileUrl = null): bool
    {
        $this->memoryCache = [];

        if ($profileUrl === null) {
            return $this->cache->clear();
        }

        // Clear profile-specific cache (would need to track keys)
        // For simplicity, clear all for now
        return $this->cache->clear();
    }

    /**
     * Check if slice matcher is cached.
     */
    public function isCached(
        string $profileUrl,
        string $elementPath,
        string $sliceName
    ): bool {
        $cacheKey = $this->buildCacheKey($profileUrl, $elementPath, $sliceName);

        if (isset($this->memoryCache[$cacheKey])) {
            return true;
        }

        return $this->cache->hasItem($cacheKey);
    }
}
