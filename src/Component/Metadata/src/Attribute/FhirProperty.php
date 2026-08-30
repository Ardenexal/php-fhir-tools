<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute;

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
 *   'choiceGroup'       — Transparent (wrapper-less) XML choice group (FHIR tooling extension
 *                         xml-choice-group). The property is an ordered list<ChoiceGroupItem> whose
 *                         heterogeneous children emit directly under the parent, in document order
 *                         (e.g. CDA AD: streetAddressLine, city, streetAddressLine). Set
 *                         isArray: true, phpType: ChoiceGroupItem::class, and variants keyed by
 *                         child element name (jsonKey = element name, phpType = the item value's
 *                         FQCN or 'string'). Unlike 'choice', isChoice stays FALSE — every variant
 *                         maps to the one list property and appends, rather than selecting a single
 *                         value[x] slot.
 *   'enum'              — Backed enum (CDA coded property bound to a generated enum, e.g. NullFlavor);
 *                         the property type IS the enum and its ->value is the code string
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FhirProperty
{
    /**
     * @param list<array{fhirType: string, propertyKind: string, phpType: string, jsonKey: string}>|null $variants
     *                                                                                                             Per-variant metadata. Populated when isChoice is true (one entry per value[x] type) OR
     *                                                                                                             when propertyKind is 'choiceGroup' (one entry per allowed child element name, jsonKey =
     *                                                                                                             element name, phpType = the item value's FQCN or 'string'). Null otherwise.
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
        /** Per-variant metadata for 'choice' (value[x]) and 'choiceGroup' properties; null otherwise. */
        public readonly ?array $variants = null,
        /**
         * JSON/XML key override. Null means use the PHP property name as-is.
         * For non-choice properties this is rare; for choice variants the jsonKey is the concrete
         * element name (e.g. 'deceasedBoolean', 'deceasedDateTime').
         */
        public readonly ?string $jsonKey = null,
        /**
         * When non-null, the property must be serialized as an XML attribute on the parent element
         * rather than a child element. The value is the XmlEncoder key, e.g. '@id' or '@url'.
         * Corresponds to FHIR StructureDefinition element.representation = ["xmlAttr"].
         */
        public readonly ?string $xmlSerializedName = null,
        /**
         * Fully-qualified PHP class name for the item type of array complex/backbone properties.
         * Null for scalars, primitives, non-array properties, and choice elements.
         * Used by the serializer to denormalize array items into typed objects.
         */
        public readonly ?string $phpType = null,
        /**
         * XML namespace URI for this element when it differs from its owning class's namespace.
         * Null means inherit the class namespace. CDA AU extension elements carry the ADHA
         * extension namespace (e.g. 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0')
         * here, sourced from the element's FHIR-tooling xml-namespace extension. The XML serializer
         * applies it when emitting the element (serialization wiring is CDA M5).
         */
        public readonly ?string $xmlNamespace = null,
    ) {
    }
}
