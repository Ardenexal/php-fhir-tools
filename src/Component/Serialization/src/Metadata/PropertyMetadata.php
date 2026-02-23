<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

/**
 * Immutable value object describing the serialization semantics of a single FHIR property.
 *
 * Hydrated once per class per PHP process (from FHIR_PROPERTY_MAP const or #[FhirProperty]
 * attribute reflection) and cached by PropertyMetadataProvider. Hot-path normalizer code
 * reads from this object instead of performing per-call type inspection.
 *
 * @author Ardenexal
 */
final class PropertyMetadata
{
    /**
     * @param string                             $fhirType     FHIR type code ('date', 'HumanName', 'choice', etc.)
     * @param string                             $propertyKind Semantic category — see FhirProperty attribute doc
     * @param bool                               $isArray      True when the property holds a list
     * @param bool                               $isRequired   True when cardinality is 1..*
     * @param bool                               $isChoice     True for choice elements (value[x])
     * @param list<PropertyVariantMetadata>|null $variants     Non-null only when isChoice is true
     * @param string|null                        $jsonKey      Key override; null = use PHP property name
     */
    public function __construct(
        public readonly string $fhirType,
        public readonly string $propertyKind,
        public readonly bool $isArray,
        public readonly bool $isRequired,
        public readonly bool $isChoice,
        public readonly ?array $variants,
        public readonly ?string $jsonKey,
    ) {
    }
}
