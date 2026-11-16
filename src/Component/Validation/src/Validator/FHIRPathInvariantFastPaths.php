<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

/**
 * Answers the commonest base invariants in PHP when the answer is certainly a pass.
 *
 * `ele-1` is declared on Element, so it runs on every element of every resource: about 600 times for
 * a 55-entry Bundle, which made it a fifth of all validation time. `ref-1` runs on every Reference
 * and was next. Each one is a FHIRPath evaluation whose answer, for nearly every node, follows from
 * one or two property reads.
 *
 * The rules this class holds to:
 *
 * - A fast path is chosen by the exact expression text, never by the invariant key. A profile or a
 *   later FHIR version that changes the expression no longer matches and goes to the engine.
 * - It only ever answers "passes". Whenever the answer might be a failure, or depends on anything
 *   it does not read, it answers null and the caller runs the engine as before. Failures are rare,
 *   so this costs nothing, and the worst a fast-path bug can do is hide a failure; it can never
 *   invent one.
 * - "Passes" covers both a true result and an empty one, because the invariant validator treats
 *   them the same way.
 *
 * `FHIRPathInvariantFastPathsCorpusTest` checks every pass against the engine across the FHIR
 * validator test corpus.
 *
 * @internal used by FHIRPathInvariantValidator, not part of the public API
 *
 * @author Ardenexal
 */
final class FHIRPathInvariantFastPaths
{
    /** Element.ele-1, as published in R4 and R4B. */
    public const string ELE_1 = 'hasValue() or (children().count() > id.count())';

    /** Element.ele-1 in R5, which also lets a Parameters resource through. */
    public const string ELE_1_R5 = 'hasValue() or (children().count() > id.count()) or $this is Parameters';

    /** Extension.ext-1, the same in every version. */
    public const string EXT_1 = 'extension.exists() != value.exists()';

    /** Reference.ref-1, R4. */
    public const string REF_1_R4 = "reference.startsWith('#').not() or (reference.substring(1).trace('url') in %rootResource.contained.id.trace('ids'))";

    /** Reference.ref-1, R4B. */
    public const string REF_1_R4B = "reference.startsWith('#').not() or (reference.substring(1).trace('url') in %rootResource.contained.id.trace('ids')) or (reference='#' and %rootResource!=%resource)";

    /** Reference.ref-1, R5 (the double space is in the published expression). */
    public const string REF_1_R5 = "reference.exists()  implies (reference.startsWith('#').not() or (reference.substring(1).trace('url') in %rootResource.contained.id.trace('ids')) or (reference='#' and %rootResource!=%resource))";

    /**
     * Whether the invariant certainly passes on this value, or null to let the engine decide.
     *
     * @param string $expression The invariant's FHIRPath expression, exactly as declared
     * @param mixed  $value      The value the invariant is evaluated on
     *
     * @return true|null True when the invariant passes; null when this class does not know
     */
    public static function passes(string $expression, mixed $value): ?true
    {
        if (!is_object($value)) {
            return null;
        }

        return match ($expression) {
            self::ELE_1, self::ELE_1_R5                     => self::elementHasContent($value),
            self::EXT_1                                     => self::extensionHasExactlyOne($value),
            self::REF_1_R4, self::REF_1_R4B, self::REF_1_R5 => self::referenceIsNotLocal($value),
            default                                         => null,
        };
    }

    /**
     * `ele-1`: the element has a value, or a child other than its `id`.
     *
     * Children are read the way the engine's `children()` reads them: the object's public
     * properties that are initialized and not null, with a list counting one child per non-null
     * item. `id` is left out of the count rather than subtracted, so an empty-string id or any other
     * case where the engine's `id.count()` might differ is never relied on.
     */
    private static function elementHasContent(object $element): ?true
    {
        // hasValue(): what HasValueFunction answers for a single object
        if (isset($element->value)) {
            return true;
        }

        // Called from this class's scope, get_object_vars() returns the public properties only and
        // leaves out typed properties that were never written, as children() sees them
        foreach (get_object_vars($element) as $name => $child) {
            if ($name === 'id' || $child === null) {
                continue;
            }

            if (!is_array($child) || !array_is_list($child)) {
                return true;
            }

            foreach ($child as $item) {
                if ($item !== null) {
                    return true;
                }
            }
        }

        return null;
    }

    /**
     * `ext-1`: the extension has sub-extensions or a value, not both.
     *
     * Only the two plain shapes are answered: sub-extensions with no value property set at all, or
     * a `value` and no sub-extensions. A value held only in a typed `value[x]` property, or anything
     * else unusual, goes to the engine.
     */
    private static function extensionHasExactlyOne(object $extension): ?true
    {
        $vars          = get_object_vars($extension);
        $subExtensions = $vars['extension'] ?? null;
        // The engine's extension.exists() counts any entry, a null one included, so any entry counts here
        $hasExtensions = is_array($subExtensions) && $subExtensions !== [];

        $hasAnyValue = false;
        foreach ($vars as $name => $child) {
            if ($child !== null && $child !== [] && str_starts_with($name, 'value')) {
                $hasAnyValue = true;

                break;
            }
        }

        if ($hasExtensions && !$hasAnyValue) {
            return true;
        }

        if (!$hasExtensions && is_array($subExtensions) && self::isPlainlyPresent($vars['value'] ?? null)) {
            return true;
        }

        return null;
    }

    /**
     * Whether `exists()` is certainly true for this value: a scalar, or an object with something in it.
     *
     * A primitive wrapper with neither a value nor extensions might read as absent, so an object
     * counts only when one of its public properties holds a scalar or a non-empty array.
     */
    private static function isPlainlyPresent(mixed $value): bool
    {
        if (is_scalar($value)) {
            return true;
        }

        if (!is_object($value)) {
            return false;
        }

        foreach (get_object_vars($value) as $child) {
            if (is_scalar($child) || (is_array($child) && $child !== [])) {
                return true;
            }
        }

        return false;
    }

    /**
     * `ref-1`: the reference is absent, or is not a `#` reference into `contained`.
     *
     * Only a plain string, or a primitive wrapper holding one, is read. A `#` reference needs
     * `%rootResource.contained`, and a wrapper with no value may still carry extensions, so both
     * go to the engine.
     */
    private static function referenceIsNotLocal(object $reference): ?true
    {
        $vars = get_object_vars($reference);

        if (!array_key_exists('reference', $vars) && property_exists($reference, 'reference')) {
            // Declared but never written: the engine reads it as absent
            return true;
        }

        $target = $vars['reference'] ?? null;

        if ($target === null) {
            return array_key_exists('reference', $vars) ? true : null;
        }

        if (is_object($target)) {
            $target = get_object_vars($target)['value'] ?? null;
        }

        if (!is_string($target)) {
            return null;
        }

        return str_starts_with($target, '#') ? null : true;
    }
}
