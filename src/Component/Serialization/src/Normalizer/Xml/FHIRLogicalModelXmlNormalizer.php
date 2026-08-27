<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml;

use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContext;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\LogicalModelLocatorTrait;
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
