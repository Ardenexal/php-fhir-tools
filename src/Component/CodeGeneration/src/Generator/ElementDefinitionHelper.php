<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\Metadata\Profile\ElementDefinitionReader;

/**
 * Utility helpers for reading FHIR StructureDefinition element definition arrays.
 *
 * Delegates to {@see ElementDefinitionReader} in Metadata, which runtime profile compilation also
 * uses, so generator and runtime read elements the same way.
 *
 * @author Ardenexal
 */
final class ElementDefinitionHelper
{
    /**
     * Returns true when the element definition delegates its structure to another element via
     * contentReference. Such elements must not receive value constraint attributes because the
     * constraints live on the referenced type, not on this element.
     *
     * @param array<string, mixed> $element
     */
    public static function hasContentReference(array $element): bool
    {
        return ElementDefinitionReader::hasContentReference($element);
    }

    /**
     * Finds a FHIR polymorphic field (e.g. fixedString, patternCodeableConcept) in an element
     * definition by scanning for a key that starts with the given prefix.
     *
     * Returns an array with 'type' (the suffix after the prefix, e.g. 'String') and 'value'
     * (the raw field value), or null when no matching key is found.
     *
     * @param array<string, mixed> $element
     *
     * @return array{type: string, value: mixed}|null
     */
    public static function extractPolymorphicField(array $element, string $prefix): ?array
    {
        return ElementDefinitionReader::extractPolymorphicField($element, $prefix);
    }

    private function __construct()
    {
    }
}
