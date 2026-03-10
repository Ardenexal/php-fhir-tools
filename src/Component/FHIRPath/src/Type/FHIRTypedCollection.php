<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Type;

/**
 * Wraps a raw FHIR data array with its canonical FHIR type name.
 *
 * Raw PHP arrays from FHIR resource deserialization lose their FHIR type context
 * when accessed via property navigation. This wrapper preserves that context so
 * that ofType(), is(), and type() can correctly identify complex FHIR types
 * (e.g. HumanName, Extension, Age) even when the underlying value is a plain array.
 *
 * FHIRPath navigation delegates through the underlying array transparently:
 * navigateProperty(FHIRTypedCollection, 'family') → navigateProperty($value->value, 'family')
 *
 * Consumers that need the underlying data call normalizeValue() or access
 * the public $value property directly.
 */
final class FHIRTypedCollection
{
    /**
     * @param array<array-key, mixed> $value    The raw FHIR data array
     * @param string                  $fhirType The canonical FHIR type name (e.g. 'HumanName', 'Extension', 'Age')
     */
    public function __construct(
        public readonly array $value,
        public readonly string $fhirType,
    ) {
    }
}
