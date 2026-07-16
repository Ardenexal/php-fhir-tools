<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

/**
 * Tolerant structural reads over deserializer-origin `QuestionnaireResponse` / `Questionnaire` item
 * objects for the `$extract` path: link ids, child items, answers, and answer values, plus the
 * string/expression primitive reads extraction relies on.
 *
 * Deliberately stricter than the populate-side {@see FhirPrimitiveReader}: `stringifyPrimitive` reads
 * *only* string-valued primitives (a non-string — e.g. a boolean/integer FHIRPath scalar at
 * `resolveFullUrl` — yields null, driving extraction's `urn:uuid` fallback). The two are NOT
 * interchangeable; see the sdc-service-decomposition M03 reconciliation note.
 *
 * Shared by {@see FHIRQuestionnaireResponseExtractService} and {@see DefinitionExtractionWalker} so both
 * read QR structure identically. All reads use `property_exists` + `?? ` isset-semantics so an
 * uninitialized typed property reads as absent rather than throwing (the model-initialization footgun).
 *
 * @internal implementation detail of the `Sdc` extraction path; not part of the public API
 */
final class QuestionnaireResponseReader
{
    /**
     * The `linkId` of a response/questionnaire item, or null when absent/unreadable.
     */
    public function linkIdOf(object $item): ?string
    {
        $linkId = property_exists($item, 'linkId') ? ($item->linkId ?? null) : null;

        return $this->stringifyPrimitive($linkId);
    }

    /**
     * The object-valued child `item`s of a node (empty when none).
     *
     * @return list<object>
     */
    public function childItems(object $object): array
    {
        // `??` uses isset() semantics, reading an uninitialized typed property as "absent" not \Error.
        $items = property_exists($object, 'item') ? ($object->item ?? []) : [];
        if (!is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, static fn (mixed $v): bool => is_object($v)));
    }

    /**
     * The object-valued `answer`s of a response item (empty when none).
     *
     * @return list<object>
     */
    public function answersOf(object $item): array
    {
        $answers = property_exists($item, 'answer') ? ($item->answer ?? []) : [];
        if (!is_array($answers)) {
            return [];
        }

        return array_values(array_filter($answers, static fn (mixed $v): bool => is_object($v)));
    }

    /**
     * The `value[x]` of a QR answer (a typed datatype object or scalar), or null when absent.
     */
    public function answerValue(object $answer): mixed
    {
        return property_exists($answer, 'value') ? ($answer->value ?? null) : null;
    }

    /**
     * Coerce a primitive-wrapper-or-string value to a plain string, or null when unreadable. Strict:
     * only string-valued inputs read; a non-string (bool/int/float/object) yields null by design.
     */
    public function stringifyPrimitive(mixed $value): ?string
    {
        if (is_string($value)) {
            return $value === '' ? null : $value;
        }
        if (is_object($value) && property_exists($value, 'value')) {
            $inner = $value->value ?? null;

            return is_string($inner) && $inner !== '' ? $inner : null;
        }

        return null;
    }

    /**
     * Extract the FHIRPath string from a `valueExpression` (an `Expression` datatype), tolerating a
     * constructor-bypassed object, or null when unreadable.
     */
    public function expressionString(mixed $value): ?string
    {
        if (is_object($value) && property_exists($value, 'expression')) {
            return $this->stringifyPrimitive($value->expression ?? null);
        }

        return null;
    }
}
