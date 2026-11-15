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
 * Mixed content: these groups are mixed element-and-text — CDA `EN` interleaves character data with
 * the name parts, and `AD` does the same with the address parts. A text run is a MEMBER of the
 * ordered list, not a property alongside it: the source StructureDefinition declares it as a slice
 * of the group (`EN.item.xmlText`) and the group's invariant counts it as a peer of the element
 * slices (`EN-1`: `(delimiter | family | given | prefix | suffix | xmlText).count() = 1`). Such a
 * member uses the reserved element name {@see self::TEXT_ELEMENT_NAME} and a string value; the
 * serializer emits it as the parent's character data rather than as a child element. Use
 * {@see self::text()} to build one.
 *
 * @author Ardenexal
 */
final readonly class ChoiceGroupItem
{
    /**
     * Reserved element name marking a member that is bare character data rather than a child
     * element. Named for the `xmlText` slice the CDA StructureDefinitions declare, which carries
     * `representation: ["xmlText"]` and — per the published IG — never appears as an element in an
     * instance. It is therefore safe as a discriminator: no CDA element is named `xmlText`.
     */
    public const string TEXT_ELEMENT_NAME = 'xmlText';

    /**
     * @param string        $elementName Local XML element name of the child (the choice discriminator),
     *                                   or {@see self::TEXT_ELEMENT_NAME} for a bare character-data member
     * @param object|string $value       The child's value: a complex datatype object, or a string for a primitive/text part
     */
    public function __construct(
        public string $elementName,
        public object|string $value,
    ) {
    }

    /**
     * A bare character-data member of the group, emitted as the parent element's text content.
     *
     * `ChoiceGroupItem::text('Example Clinic')` in an `ON`'s item list produces
     * `<name>Example Clinic</name>`. Equivalent to passing {@see self::TEXT_ELEMENT_NAME} directly,
     * which remains valid.
     *
     * @param string $characterData The run of text to place between the group's element members
     *
     * @return self A member the serializer writes as text rather than as an element
     */
    public static function text(string $characterData): self
    {
        return new self(self::TEXT_ELEMENT_NAME, $characterData);
    }
}
