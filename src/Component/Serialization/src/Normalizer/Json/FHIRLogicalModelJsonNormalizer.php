<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json;

use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\LogicalModelLocatorTrait;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common\AbstractFHIRNormalizer;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;

/**
 * Guard normalizer that rejects JSON serialization of XML-only CDA logical models.
 *
 * CDA datatypes and clinical classes are XML-only (HL7 V3 has no JSON wire format). They carry a
 * non-null #[LogicalModel]->xmlNamespace. Attempting to serialize such an object to JSON would
 * otherwise fall through to the complex-type JSON normalizer and emit a structurally invalid
 * document, so this normalizer claims those objects for JSON and throws a descriptive error
 * pointing the caller at serializeToXml() instead.
 *
 * @author Ardenexal
 */
class FHIRLogicalModelJsonNormalizer extends AbstractFHIRNormalizer
{
    use LogicalModelLocatorTrait;

    public function __construct(
        FHIRMetadataExtractorInterface $metadataExtractor,
        ?NormalizerInterface $normalizer = null,
        ?DenormalizerInterface $denormalizer = null,
        string $fhirVersion = 'R4',
        ?FHIRIGTypeRegistry $igTypeRegistry = null,
    ) {
        parent::__construct($metadataExtractor, $normalizer, $denormalizer, $fhirVersion, $igTypeRegistry);
    }

    /**
     * {@inheritDoc}
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>|string|int|float|bool|\ArrayObject<string, mixed>|null
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $name = is_object($object) ? substr((string) strrchr('\\' . $object::class, '\\'), 1) : gettype($object);

        throw new InvalidArgumentException(sprintf('CDA logical model "%s" cannot be serialized to JSON: CDA/HL7 V3 is an XML-only format. Use serializeToXml() instead.', $name));
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if ($format === 'xml') {
            return false;
        }

        if (!is_object($data)) {
            return false;
        }

        return $this->xmlOnlyLogicalModel($data);
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        throw new InvalidArgumentException(sprintf('CDA logical model "%s" cannot be deserialized from JSON: CDA/HL7 V3 is an XML-only format. Use deserializeFromXml() instead.', $type));
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if ($format === 'xml') {
            return false;
        }

        return $this->findLogicalModelAttribute($type)?->xmlNamespace !== null;
    }

    /**
     * @return array<string, bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return ['object' => false];
    }

    /**
     * True when the object's class (or an ancestor) is a logical model with a non-null XML namespace,
     * i.e. an XML-only CDA type.
     */
    private function xmlOnlyLogicalModel(object $object): bool
    {
        return $this->findLogicalModelAttribute($object)?->xmlNamespace !== null;
    }
}
