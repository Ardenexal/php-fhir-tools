<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml;

use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Metadata\Type\LogicalModelLocatorTrait;
use Ardenexal\FHIRTools\Component\Metadata\Type\PropertyMetadata;
use Ardenexal\FHIRTools\Component\Metadata\Type\PropertyVariantMetadata;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

/**
 * XML normalizer for CDA logical-model classes (kind=logical, derivation=specialization).
 *
 * CDA datatypes (II, CS, CD, …) and clinical classes (ClinicalDocument, Section, …) carry the
 * class-level #[LogicalModel] attribute rather than #[FHIRComplexType]/#[FhirResource], so they
 * are not picked up by the standard complex-type/resource normalizers. This normalizer routes
 * any #[LogicalModel] object through the inherited complex-type XML loop (which already emits
 * #[FhirProperty]->xmlSerializedName properties as XML attributes) and additionally declares the
 * model's XML namespace (e.g. urn:hl7-org:v3) on the document root element.
 *
 * The namespace is declared only on the outermost element: nested CDA datatypes route back through
 * this same normalizer recursively, and re-declaring xmlns on every child both bloats the output
 * and breaks byte-level round-trips against published CDA examples. Root detection uses a context
 * flag that is set on the context handed to child normalize() calls.
 *
 * The root also declares the XML Schema instance namespace, and this normalizer writes an xsi:type
 * attribute onto every element whose declared type admits several datatypes — CDA's way of saying
 * which datatype a polymorphic element holds, since the element name stays fixed. Without it a
 * <value> is ambiguous between text, a code and a measurement, and a schema-validating receiver
 * rejects it.
 *
 * @author Ardenexal
 */
class FHIRLogicalModelXmlNormalizer extends FHIRComplexTypeXmlNormalizer
{
    use LogicalModelLocatorTrait;

    /**
     * Context key marking that the current object is nested inside an already-serialized CDA root,
     * so its class namespace must not be re-declared.
     */
    private const NESTED_FLAG = '__cda_nested';

    /**
     * The XML Schema instance namespace, whose `type` attribute names the concrete datatype occupying
     * a polymorphic element. CDA relies on it to resolve any element the schema declares as `ANY`.
     */
    private const XSI_NAMESPACE = 'http://www.w3.org/2001/XMLSchema-instance';

    /**
     * Attribute key the encoder renders as `xsi:type`, bound by the root's namespace declaration.
     */
    private const XSI_TYPE_KEY = '@xsi:type';

    /**
     * {@inheritDoc}
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>|string|int|float|bool|\ArrayObject<string, mixed>|null
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException('Expected object, got ' . gettype($object));
        }

        $xmlNamespace = $this->logicalModelXmlNamespace($object);
        $isRoot       = !($context[self::NESTED_FLAG] ?? false);

        // Mark every child (recursively serialized via the injected Serializer) as nested so they
        // do not re-declare the namespace.
        $childContext                    = $context;
        $childContext[self::NESTED_FLAG] = true;

        $data = $this->normalizeForXML($object, FHIRSerializationContext::fromSymfonyContext($childContext), $childContext);
        $data = $this->stampPolymorphicTypes($object, $data);

        if ($isRoot && $xmlNamespace !== null) {
            // Declare the root namespace FIRST so XmlEncoder sets it on the root element before it
            // imports any pre-namespaced DOM children (e.g. an xml-choice-group DOMDocumentFragment
            // emitted under the '#' key). If @xmlns were appended last, those children would be
            // imported into a not-yet-namespaced root and libxml would redundantly re-declare
            // xmlns on each one. Prepending keeps the namespace declared exactly once, on the root.
            // xmlns:xsi joins it unconditionally rather than on demand. A datatype attribute is written
            // deep inside the document — on an observation's value, while the root is the document —
            // and the context only travels DOWNWARD here, so a descendant that needs the prefix has no
            // way to tell the root to declare it. Declaring it always costs one attribute on the root
            // and is what published CDA instances do anyway.
            $data = ['@xmlns' => $xmlNamespace, '@xmlns:xsi' => self::XSI_NAMESPACE] + $data;
        }

        return $data;
    }

    /**
     * Write the published datatype name onto every element whose declared type varies.
     *
     * CDA discriminates a polymorphic element with an `xsi:type` attribute rather than by changing the
     * element name, so the name alone leaves `<value>No Recommendations.</value>` ambiguous between
     * text, a code and a measurement — well-formed, plausible, and rejected by a schema-validating
     * receiver. Only an element whose DECLARED type varies is stamped: the schema wants the attribute
     * where the instance type differs from the declared one, and adding it to a single-typed element
     * would change output that receivers already accept.
     *
     * Runs after the inherited emit loop rather than inside it. The loop is shared with FHIR, where
     * this attribute has no meaning, and post-processing keeps one CDA concern in the CDA normalizer.
     *
     * @param object               $object The CDA object being serialized
     * @param array<string, mixed> $data   Its normalized form, as the inherited emit loop produced it
     *
     * @return array<string, mixed> The same data, with a datatype attribute on each polymorphic element
     */
    private function stampPolymorphicTypes(object $object, array $data): array
    {
        foreach ($this->getPropertyMetadataMap($object) as $propertyName => $meta) {
            if ($meta->propertyKind !== 'polymorphic' || $meta->variants === null) {
                continue;
            }

            $key = $meta->jsonKey ?? $propertyName;
            if (!array_key_exists($key, $data) || !isset($object->{$propertyName})) {
                continue;
            }

            $value      = $object->{$propertyName};
            $data[$key] = is_array($value)
                ? $this->stampEachOccurrence($value, $data[$key], $meta->variants)
                : $this->stampOne($value, $data[$key], $meta->variants);
        }

        return $data;
    }

    /**
     * Stamp a repeating polymorphic element, pairing each occurrence with its own normalized form.
     *
     * The emit loop wraps a repeating property's members in a list even when there is only one, so
     * `[$one]` normalizes to `['value' => [0 => [...]]]` and the attribute belongs on the member, not
     * on the wrapper. Stamping the wrapper instead mixes a string key into a list, and the encoder
     * then renders the members as `<item key="0">` — losing the element name entirely.
     *
     * The normalized side drives the pairing, because it is what gets emitted; a member the source
     * cannot account for is passed through rather than dropped.
     *
     * @param array<array-key, mixed>       $values     The occupying values, in order; read straight off
     *                                                  the property, so not guaranteed to be a list
     * @param mixed                         $normalized Their normalized form
     * @param list<PropertyVariantMetadata> $variants   Candidate datatypes, subclass-before-superclass
     *
     * @return mixed The normalized form with a datatype attribute on each occurrence
     */
    private function stampEachOccurrence(array $values, mixed $normalized, array $variants): mixed
    {
        if (!is_array($normalized)) {
            return $normalized;
        }

        // An associative array is one occurrence emitted directly rather than a list of members.
        if ($normalized !== [] && !array_is_list($normalized)) {
            $first = array_values($values)[0] ?? null;

            return $first === null ? $normalized : $this->stampOne($first, $normalized, $variants);
        }

        $sourceValues = array_values($values);
        $stamped      = [];
        foreach ($normalized as $index => $member) {
            $value           = $sourceValues[$index] ?? null;
            $stamped[$index] = $value === null ? $member : $this->stampOne($value, $member, $variants);
        }

        return $stamped;
    }

    /**
     * Stamp one occurrence, leaving it untouched when no variant matches or it is not an element.
     *
     * A value matching no variant is left alone deliberately: the alternative is inventing a datatype
     * name, and a wrong name is worse than a missing one — a receiver rejects the missing name, while
     * a wrong one can be accepted as a different datatype entirely.
     *
     * @param mixed                         $value      The value occupying this occurrence
     * @param mixed                         $normalized Its normalized form
     * @param list<PropertyVariantMetadata> $variants   Candidate datatypes, subclass-before-superclass
     *
     * @return mixed The normalized form, carrying a datatype attribute when one could be resolved
     */
    private function stampOne(mixed $value, mixed $normalized, array $variants): mixed
    {
        if (!is_array($normalized)) {
            return $normalized;
        }

        $variant = $this->resolveVariant($value, $variants);
        if ($variant?->typeName === null) {
            return $normalized;
        }

        // Prepend so the discriminator reads first on the element, before the datatype's own attributes.
        return [self::XSI_TYPE_KEY => $variant->typeName] + $normalized;
    }

    /**
     * {@inheritDoc}
     *
     * Reads the datatype the document declared for a polymorphic element and returns the class that
     * implements it, so `<value xsi:type="ST">` deserializes to an ST rather than to the abstract
     * datatype the property is declared as.
     *
     * The attribute is read by NAMESPACE, never by prefix. `xsi` is only a convention — a document may
     * bind any prefix to the schema-instance namespace, and the decoder keys the decoded array on the
     * raw prefixed name, so matching the string `@xsi:type` would silently miss a conformant document
     * that chose `xs` or `x`. Matching the namespace also refuses a `type` attribute in some unrelated
     * namespace, which prefix matching would happily accept.
     *
     * A missing or unrecognised datatype is reported rather than guessed. Picking a datatype would
     * invent information the document does not carry, and letting the declared type through raises
     * "Cannot instantiate abstract class" against a datatype the document never mentions. Null is
     * returned only when no source element was threaded down, leaving the declared type in place.
     *
     * @param PropertyMetadata|null $meta   Metadata for the property being filled, when known
     * @param \DOMElement|null      $source This element in the source document, when one was threaded down
     *
     * @return string|null The class implementing the declared datatype, or null when none was resolved
     */
    protected function resolvePolymorphicItemClass(?PropertyMetadata $meta, ?\DOMElement $source): ?string
    {
        if ($meta === null || $meta->propertyKind !== 'polymorphic' || $meta->variants === null) {
            return null;
        }

        if ($source === null) {
            return null;
        }

        $declaredType = $source->hasAttributeNS(self::XSI_NAMESPACE, 'type')
            ? $source->getAttributeNS(self::XSI_NAMESPACE, 'type')
            : '';

        foreach ($meta->variants as $variant) {
            if ($variant->typeName === $declaredType) {
                return ltrim($variant->phpType, '\\');
            }
        }

        // Nothing resolved. Reporting it beats letting the declared type through: that type is abstract
        // for a polymorphic element, so PHP would raise "Cannot instantiate abstract class" naming a
        // datatype the document never mentions, which says nothing about what is actually wrong.
        $permitted = array_values(array_filter(array_map(
            static fn (PropertyVariantMetadata $variant): ?string => $variant->typeName,
            $meta->variants,
        )));

        throw FHIRSerializationException::validationError($declaredType === '' ? sprintf('Element <%s> may hold several datatypes, so it must carry an xsi:type attribute naming the one it holds. Permitted: %s.', $source->localName, implode(', ', $permitted)) : sprintf('Element <%s> declares xsi:type="%s", which is not a datatype it may hold. Permitted: %s.', $source->localName, $declaredType, implode(', ', $permitted)), $source->getNodePath(), ['declared_type' => $declaredType, 'permitted_types' => $permitted]);
    }

    /**
     * {@inheritDoc}
     *
     * A polymorphic CDA element can hold a text-bearing datatype — `<value xsi:type="ST">` carries its
     * content as text, not as an attribute — so the shared unwrap cannot be used here: it strips every
     * `#`-prefixed key, and `#` is where the decoder puts that text. The result reads back with correct
     * attributes and an empty value, which is the reported defect one level down.
     *
     * Comment and CDATA markers are still dropped; only the text-content key survives. The shared
     * unwrap's `@value` collapse is deliberately not reproduced: it fires only when a decoded element
     * has no keys besides `@value` and `#`, and a polymorphic element always carries its datatype
     * attribute as well, so it could never apply here.
     *
     * @param PropertyMetadata|null $meta  Metadata for the property being filled, when known
     * @param mixed                 $value The property's decoded value
     *
     * @return mixed The items to denormalize, one per occurrence
     */
    protected function prepareComplexArrayItems(?PropertyMetadata $meta, mixed $value): mixed
    {
        if ($meta?->propertyKind !== 'polymorphic') {
            return parent::prepareComplexArrayItems($meta, $value);
        }

        if (!is_array($value)) {
            return [$value];
        }

        return self::stripXmlMarkersKeepingText($value);
    }

    /**
     * Drop the decoder's comment and CDATA markers while keeping an element's text content.
     *
     * @param array<mixed> $data A decoded element, or a list of them
     *
     * @return array<mixed> The same structure without comment or CDATA markers
     */
    private static function stripXmlMarkersKeepingText(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_string($key) && str_starts_with($key, '#') && $key !== '#') {
                continue;
            }
            $result[$key] = is_array($value) ? self::stripXmlMarkersKeepingText($value) : $value;
        }

        return $result;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if ($format !== 'xml') {
            return false;
        }

        if (!is_object($data)) {
            return false;
        }

        return $this->isLogicalModel($data);
    }

    /**
     * Route #[LogicalModel] types (CDA datatypes and clinical classes) through the inherited
     * complex-type XML denormalize loop. Enabled in M7 to support XML round-tripping of CDA logical
     * models, in particular transparent xml-choice-group properties whose document order the
     * denormalizer recovers from the source DOM element.
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if ($format !== 'xml' || !is_array($data)) {
            return false;
        }

        return $this->findLogicalModelAttribute($type) !== null;
    }

    /**
     * @return array<string, bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return ['object' => false];
    }

    /**
     * {@inheritDoc}
     *
     * CDA fixes the order of an element's children, and the generator records it on the class as
     * `propertyOrder` because nothing at runtime can reconstruct it: reflection reports a class's own
     * properties before its ancestors', so `InfrastructureRoot`'s `realmCode`/`typeId`/`templateId` —
     * first in the content model — arrive last on every act, and AU's `completionCode` is declared on
     * the child yet belongs mid-sequence in `ClinicalDocument`'s elements.
     *
     * The nearest attribute in the hierarchy is the right one: every generated CDA class carries its
     * own, holding the complete list for that concrete type. The list is empty on a class generated
     * before the field existed, and empty means "keep reflection order".
     */
    protected function contentModelOrder(object $object): array
    {
        // `??` already covers a null attribute — it suppresses the whole property chain — so nullsafe
        // access here would be redundant.
        return $this->findLogicalModelAttribute($object)->propertyOrder ?? [];
    }

    /**
     * Read the XML namespace declared by the object's (or an ancestor's) #[LogicalModel] attribute.
     */
    private function logicalModelXmlNamespace(object $object): ?string
    {
        return $this->findLogicalModelAttribute($object)?->xmlNamespace;
    }

    /**
     * True when the object's class (or an ancestor — AU classes extend their core counterparts)
     * carries the #[LogicalModel] attribute.
     */
    private function isLogicalModel(object $object): bool
    {
        return $this->findLogicalModelAttribute($object) !== null;
    }
}
