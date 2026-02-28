<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Attributes;

/**
 * Attribute encoding static property semantics for FHIR model properties.
 *
 * Applied to every property of a generated FHIR model class, allowing the serializer to read
 * property semantics from a compiled map rather than rediscovering them via runtime heuristics
 * on every serialization call.
 *
 * propertyKind values:
 *   'scalar'            — PHP builtin (?bool, ?int, ?string); no extension support
 *   'primitive'         — FHIR primitive wrapper (?DatePrimitive); has $value + extension support
 *   'complex'           — FHIR complex type (?HumanName, array<Reference>)
 *   'backbone'          — Backbone element (array<PatientContact>)
 *   'resource'          — Full FHIR resource (contained resources)
 *   'extension'         — Extension array (named 'extension')
 *   'modifierExtension' — ModifierExtension array (named 'modifierExtension')
 *   'choice'            — Polymorphic value[x] / deceased[x] — must set isChoice: true and variants
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FhirProperty
{
    /**
     * @param list<array{fhirType: string, propertyKind: string, phpType: string, jsonKey: string}>|null $variants
     *                                                                                                             Populated only when isChoice is true. Each variant describes one concrete type in the union.
     * @param string|null                                                                                  $phpClass
     *                                                                                                             Fully-qualified PHP class name for each element of an array-typed complex or backbone
     *                                                                                                             property (e.g. 'Ardenexal\...\R4\DataType\HumanName'). Null for non-array, primitive,
     *                                                                                                             scalar, choice, and resource properties. Used by the serializer to denormalize array
     *                                                                                                             elements to typed objects without namespace heuristics.
     */
    public function __construct(
        /** FHIR type code: 'date', 'HumanName', 'BackboneElement', 'choice', etc. */
        public readonly string $fhirType,
        /** Semantic category of this property — see propertyKind table in class doc. */
        public readonly string $propertyKind,
        /** True when the property holds a list (array<T>). */
        public readonly bool $isArray = false,
        /** True when the element is required in the FHIR spec (cardinality 1..*). */
        public readonly bool $isRequired = false,
        /** True for choice elements (value[x], deceased[x]). Requires variants to be set. */
        public readonly bool $isChoice = false,
        /** Per-variant metadata for choice elements; null for non-choice properties. */
        public readonly ?array $variants = null,
        /**
         * JSON/XML key override. Null means use the PHP property name as-is.
         * For non-choice properties this is rare; for choice variants the jsonKey is the concrete
         * element name (e.g. 'deceasedBoolean', 'deceasedDateTime').
         */
        public readonly ?string $jsonKey = null,
        public readonly ?string $phpClass = null,
    ) {
    }
}
