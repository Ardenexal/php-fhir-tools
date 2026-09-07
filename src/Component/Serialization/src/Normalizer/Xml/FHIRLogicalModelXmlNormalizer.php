<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml;

use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Metadata\Type\LogicalModelLocatorTrait;
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
 * and breaks byte-level round-trips against published CDA examples. The root is the element with no
 * namespace yet in scope, tracked on the context handed to child normalize() calls.
 *
 * That scope is a namespace rather than a yes/no nesting flag because `@xmlns` is a default-namespace
 * *redefinition*, inherited by the element's entire subtree. An sdtc or AU extension element
 * therefore pulls everything beneath it into the extension namespace unless something declares
 * otherwise, and knowing only that an object is nested cannot tell it whether it needs to. Nested
 * types supply their own namespace through contentNamespace(), which normalizeForXML() applies per
 * child element wherever it differs from the scope in force.
 *
 * @author Ardenexal
 */
class FHIRLogicalModelXmlNormalizer extends FHIRComplexTypeXmlNormalizer
{
    use LogicalModelLocatorTrait;

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

        // No namespace in scope yet means nothing above has declared one, which only happens at the
        // document root. Everywhere else the key holds the namespace this element actually sits in —
        // the root's own for ordinary CDA content, or an sdtc/AU extension namespace for content
        // nested under an extension element.
        $inheritedNamespace = $context[self::XML_DEFAULT_NAMESPACE_CONTEXT_KEY] ?? null;
        $isRoot             = $inheritedNamespace === null;

        // The root declares its namespace below, so from its children's point of view that is what
        // is in scope. A nested object declares nothing here: the element it occupies was already
        // placed in a namespace by the property that named it, and re-declaring on the element would
        // both bloat the output and contradict that placement. Its own namespace is instead applied
        // per child element by normalizeForXML(), via contentNamespace().
        $childContext = $context;
        if ($isRoot && $xmlNamespace !== null) {
            $childContext[self::XML_DEFAULT_NAMESPACE_CONTEXT_KEY] = $xmlNamespace;
        }

        $data = $this->normalizeForXML($object, FHIRSerializationContext::fromSymfonyContext($childContext), $childContext);

        if ($isRoot && $xmlNamespace !== null) {
            // Declare the root namespace FIRST so XmlEncoder sets it on the root element before it
            // imports any pre-namespaced DOM children (e.g. an xml-choice-group DOMDocumentFragment
            // emitted under the '#' key). If @xmlns were appended last, those children would be
            // imported into a not-yet-namespaced root and libxml would redundantly re-declare
            // xmlns on each one. Prepending keeps the namespace declared exactly once, on the root.
            $data = ['@xmlns' => $xmlNamespace] + $data;
        }

        return $data;
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
     * {@inheritDoc}
     *
     * A CDA type declares the namespace its own elements live in, and that declaration is what makes
     * extension content terminate correctly. `AuEmployerOrganization` is reached through a property
     * in the AU extension namespace, yet its `id`/`name`/`asOrganizationPartOf` are ordinary CDA
     * elements in `urn:hl7-org:v3` — the property namespace names the wrapping element only, and
     * says nothing about what the wrapped type's content model is.
     *
     * Reading it from the type rather than from the enclosing scope is the whole point: a namespace
     * is part of element identity, so `<name>` in the AU namespace is a different element from CDA's
     * `<name>`, and a validating consumer reading the AU one sees an organisation with no name at
     * all. Nothing about that is visible to an assertion written with `local-name()`.
     *
     * @param object $object the CDA logical-model instance being serialized
     *
     * @return string|null the type's declared XML namespace, or null when it declares none
     */
    protected function contentNamespace(object $object): ?string
    {
        return $this->logicalModelXmlNamespace($object);
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
