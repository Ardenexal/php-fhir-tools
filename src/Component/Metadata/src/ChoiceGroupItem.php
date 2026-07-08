<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata;

/**
 * One member of a transparent (wrapper-less) XML choice group.
 *
 * Some elements carry the FHIR tooling extension
 * `http://hl7.org/fhir/tools/StructureDefinition/xml-choice-group`, meaning their heterogeneous
 * child elements appear directly under the parent — with no intervening wrapper element — and in
 * significant document order (e.g. a CDA `AD` postal address: `streetAddressLine`, `city`,
 * `streetAddressLine`, …). Such a property is modelled as an ordered `list<ChoiceGroupItem>`, where
 * each item pairs the child's XML element name (the discriminator) with its value, and the list
 * order IS the document order.
 *
 * The type is intentionally generic — not tied to any particular FHIR/CDA datatype — so the
 * serializer can drive any `xml-choice-group` element from metadata alone:
 *  - `$elementName` is the local XML element name, e.g. `streetAddressLine`. It is the discriminator
 *    for the choice (NOT a class name and NOT an attribute such as `@partType`).
 *  - `$value` is the child's value: a complex datatype object (e.g. an ADXP) or a bare string for a
 *    primitive/text part.
 *
 * Round-trip note: the serializer reads `$value` reflectively/via the registered variant `phpType`,
 * so this type carries no compile-time dependency on the concrete datatype classes it wraps.
 *
 * Boundary (see M7 plan, task 6): a pure-text choice-group member that has NO element name (e.g. the
 * `xmlText` string slice of CDA `AD`) is not yet representable here — `$elementName` is required.
 * The mixed element-and-text case must be designed deliberately before that slice is supported.
 *
 * @author Ardenexal
 */
final readonly class ChoiceGroupItem
{
    /**
     * @param string        $elementName Local XML element name of the child (the choice discriminator)
     * @param object|string $value       The child's value: a complex datatype object, or a string for a primitive/text part
     */
    public function __construct(
        public string $elementName,
        public object|string $value,
    ) {
    }
}
