<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Populate;

use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnairePopulateService;

/**
 * Reads FHIR primitive-wrapper-or-scalar values off deserializer-origin models, tolerating
 * constructor-bypassed objects whose intermediate `value` property is uninitialized.
 *
 * Shared by the SDC population collaborators ({@see FHIRQuestionnairePopulateService},
 * {@see AnswerValueCoercer}) — every value read from a deserialized Questionnaire/Observation passes
 * through here so a missing or unreadable primitive collapses to null rather than throwing.
 *
 * @internal implementation detail of the `Sdc` population path; not part of the public API
 */
final class FhirPrimitiveReader
{
    /**
     * The `code` of a coded primitive (`CodePrimitive`/enum-backed value), or null when absent/empty.
     */
    public function codeOf(mixed $type): ?string
    {
        if ($type === null) {
            return null;
        }

        if (\is_object($type) && property_exists($type, 'value')) {
            $inner = $type->value ?? null;
            if ($inner instanceof \BackedEnum) {
                return (string) $inner->value;
            }

            return \is_string($inner) && $inner !== '' ? $inner : null;
        }

        return $this->stringify($type);
    }

    /**
     * Coerce a primitive-wrapper-or-scalar value to a plain string, tolerating a constructor-bypassed
     * object (uninitialized `value` read via `isset`), or null when unreadable/empty.
     */
    public function stringify(mixed $value): ?string
    {
        if (\is_string($value)) {
            return $value === '' ? null : $value;
        }

        if (\is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (\is_int($value) || \is_float($value)) {
            return (string) $value;
        }

        if (\is_object($value) && property_exists($value, 'value')) {
            $inner = $value->value ?? null;

            if (\is_string($inner)) {
                return $inner === '' ? null : $inner;
            }
            if (\is_object($inner) && method_exists($inner, '__toString')) {
                $string = (string) $inner;

                return $string === '' ? null : $string;
            }
        }

        return null;
    }

    /**
     * Whether a value is a *present* empty string — a bare `''` or a primitive wrapper whose inner `value`
     * is `''` — as opposed to an absent/unreadable value. {@see stringify} collapses both empty and absent
     * to null, so this distinguishes "answered with an empty string" (not a type mismatch) from a genuinely
     * unusable value; only the former is treated as "not answered".
     */
    public function isEmptyString(mixed $value): bool
    {
        if (\is_string($value)) {
            return $value === '';
        }

        if (\is_object($value) && property_exists($value, 'value')) {
            return ($value->value ?? null) === '';
        }

        return false;
    }

    /**
     * Whether a string is a plain base-10 integer (optionally signed) — used to accept an integer answer
     * the FHIRPath engine returned as a numeric string without misreading a decimal as an integer.
     */
    public function isIntegerString(string $value): bool
    {
        return preg_match('/^[+-]?\d+$/', $value) === 1;
    }

    /**
     * Parse a FHIR temporal string to a Unix timestamp, or null when absent or unparseable.
     *
     * A timezone-less input (e.g. a date-only `"2024-01-01"`) is interpreted as UTC so window comparisons
     * are deterministic regardless of the process default timezone; the UTC hint is ignored when the string
     * already carries an offset (`Z`/`+hh:mm`).
     */
    public function parseTimestamp(?string $value): ?int
    {
        if ($value === null) {
            return null;
        }

        try {
            return (new \DateTimeImmutable($value, new \DateTimeZone('UTC')))->getTimestamp();
        } catch (\Throwable) {
            return null;
        }
    }
}
