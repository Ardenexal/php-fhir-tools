<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Determines whether a single FHIR array item belongs to a named slice by evaluating
 * the slice's discriminator rule against the item.
 *
 * Supported discriminator types:
 *   - 'value'  — The item has a specific value at the given path (exact match, string-coerced)
 *   - 'pattern' — The item contains a pattern at the given path (subset/recursive match)
 *   - 'exists'  — The discriminator value is a boolean: true means the path element must be
 *                 present (non-null), false means it must be absent (null)
 *
 * Unsupported types ('type', 'profile'):
 *   These require full profile resolution and are out of scope for this milestone.
 *   The matcher returns false and emits a warning. Items using these types are never matched
 *   to any slice, which is safe for open slicing (items pass through) but may produce
 *   spurious "unknown slice" violations for closed slicing.
 *
 * @author Ardenexal
 */
final class SliceDiscriminatorMatcher
{
    public function __construct(
        private readonly PropertyAccessorInterface $propertyAccessor,
    ) {
    }

    /**
     * Returns true if $item matches the given discriminator.
     *
     * @param object|array<string, mixed> $item               The slice array item to check
     * @param string                      $discriminatorType  'value', 'pattern', or 'exists'
     * @param string                      $discriminatorPath  Property path within the item (e.g. 'system')
     * @param mixed                       $discriminatorValue The value to match/compare against
     */
    public function matches(
        object|array $item,
        string $discriminatorType,
        string $discriminatorPath,
        mixed $discriminatorValue,
    ): bool {
        return match ($discriminatorType) {
            'value'   => $this->matchesValue($item, $discriminatorPath, $discriminatorValue),
            'pattern' => $this->matchesPattern($item, $discriminatorPath, $discriminatorValue),
            'exists'  => $this->matchesExists($item, $discriminatorPath, $discriminatorValue),
            default   => $this->unsupportedType($discriminatorType),
        };
    }

    /**
     * Exact value match. FHIR primitive wrappers implementing Stringable are compared as strings.
     * Null item values do NOT match any non-null discriminator value.
     *
     * @param object|array<string, mixed> $item
     */
    private function matchesValue(
        object|array $item,
        string $path,
        mixed $expected,
    ): bool {
        $actual = $this->readPath($item, $path);

        if ($actual === null) {
            return false;
        }

        // Coerce both sides to string for FHIR primitives (UriPrimitive, StringPrimitive, etc.)
        if ($actual instanceof \Stringable || is_string($actual)) {
            return (string) $actual === (string) $expected;
        }

        return $actual === $expected;
    }

    /**
     * Recursive subset match: every key in $expected must exist in $actual with equal values.
     * This is the same logic as FHIRPatternValueValidator::isSubset().
     *
     * @param object|array<string, mixed> $item
     */
    private function matchesPattern(
        object|array $item,
        string $path,
        mixed $expected,
    ): bool {
        $actual = $this->readPath($item, $path);

        if ($actual === null) {
            return false;
        }

        if (!is_array($expected)) {
            // Scalar pattern — fall back to value matching
            return $this->matchesValue($item, $path, $expected);
        }

        $actualArray = $actual instanceof \JsonSerializable
            ? (array) $actual->jsonSerialize()
            : (array) $actual;

        return $this->isSubset($expected, $actualArray);
    }

    /**
     * Exists discriminator: $expected is a boolean.
     * true  → path element must be present (non-null)
     * false → path element must be absent (null)
     *
     * @param object|array<string, mixed> $item
     */
    private function matchesExists(
        object|array $item,
        string $path,
        mixed $expected,
    ): bool {
        $actual  = $this->readPath($item, $path);
        $present = $actual !== null;

        return (bool) $expected === $present;
    }

    /**
     * Returns false and logs a deprecation for unsupported discriminator types.
     * 'type' and 'profile' require full profile resolution (out of scope for M07).
     */
    private function unsupportedType(string $type): bool
    {
        trigger_error(
            "FHIRSliceDiscriminatorMatcher: discriminator type '{$type}' is not supported "
            . 'in this milestone. Slice items using this type will never match.',
            E_USER_WARNING,
        );

        return false;
    }

    /**
     * Read the value at a dot-notation property path using Symfony PropertyAccessor.
     * Returns null if the path is not readable or the value is null.
     *
     * @param object|array<string, mixed> $item
     */
    private function readPath(object|array $item, string $path): mixed
    {
        if ($path === '') {
            return null;
        }

        try {
            return $this->propertyAccessor->getValue($item, $path);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Recursively checks that all keys in $pattern are present in $value with matching values.
     *
     * @param array<string, mixed> $pattern
     * @param array<string, mixed> $value
     */
    private function isSubset(array $pattern, array $value): bool
    {
        foreach ($pattern as $key => $expected) {
            if (!array_key_exists($key, $value)) {
                return false;
            }

            if (is_array($expected)) {
                if (!is_array($value[$key])) {
                    return false;
                }

                // For list-type arrays (e.g. coding[]), match if any element is a superset
                if (array_is_list($expected)) {
                    foreach ($expected as $expectedItem) {
                        $found = false;
                        foreach ($value[$key] as $actualItem) {
                            if ($this->isSubset((array) $expectedItem, (array) $actualItem)) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            return false;
                        }
                    }
                } elseif (!$this->isSubset($expected, $value[$key])) {
                    return false;
                }
            } elseif ((string) $value[$key] !== (string) $expected) {
                return false;
            }
        }

        return true;
    }
}
