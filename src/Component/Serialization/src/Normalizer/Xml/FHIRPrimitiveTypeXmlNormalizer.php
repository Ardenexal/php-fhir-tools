<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTemporalValue;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common\AbstractFHIRNormalizer;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * XML normalizer for FHIR primitive types.
 *
 * @author Ardenexal
 */
class FHIRPrimitiveTypeXmlNormalizer extends AbstractFHIRNormalizer
{
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
        if (!is_object($object)) {
            throw new InvalidArgumentException('Expected object, got ' . gettype($object));
        }

        if (!$this->metadataExtractor->isPrimitiveType($object)) {
            throw new InvalidArgumentException('Object is not a FHIR primitive type');
        }

        return $this->normalizeForXML($object, self::reflClass($object), $context);
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if ($format !== 'xml') {
            return false;
        }

        if (!is_object($data)) {
            return false;
        }

        return $this->metadataExtractor->isPrimitiveType($data);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (is_scalar($data) || is_null($data)) {
            return $this->createPrimitiveInstance($type, $data, null);
        }

        if (is_array($data)) {
            return $this->denormalizeFromXML($data, $type, $context);
        }

        throw new NotNormalizableValueException(sprintf('Cannot denormalize data of type %s to %s', gettype($data), $type));
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if ($format !== 'xml') {
            return false;
        }

        return $this->hasFHIRPrimitiveAttribute($type);
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        return ['object' => true];
    }

    /**
     * @param \ReflectionClass<object> $reflection
     * @param array<string, mixed>     $context
     *
     * @return array<string, mixed>
     */
    private function normalizeForXML(object $object, \ReflectionClass $reflection, array $context): array
    {
        $result = [];

        $valueProp = self::reflProp($object, 'value');
        if ($valueProp !== null) {
            $value = $valueProp->isInitialized($object) ? $valueProp->getValue($object) : null;

            if ($value instanceof FHIRTemporalValue) {
                $value = (string) $value;
            }

            // XmlEncoder casts PHP booleans to int (true→1, false→0). FHIR XML requires "true"/"false".
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            if ($value !== null) {
                $result['@value'] = $value;
            }
        }

        $extensionProp = self::reflProp($object, 'extension');
        if ($extensionProp !== null) {
            $extensions = $extensionProp->isInitialized($object) ? $extensionProp->getValue($object) : null;

            if ($extensions !== null && !empty($extensions)) {
                $result['extension'] = $this->normalizer !== null
                    ? $this->normalizer->normalize($extensions, 'xml', $context)
                    : $extensions;
            }
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $context
     */
    private function denormalizeFromXML(array $data, string $type, array $context): mixed
    {
        $data = $this->cleanXmlArtifacts($data);

        $value      = null;
        $extensions = null;

        if (isset($data['value'])) {
            $value = $data['value'];
        }

        if (isset($data['extension'])) {
            $raw        = $data['extension'];
            $extensions = (is_array($raw) && !array_is_list($raw)) ? [$raw] : $raw;
        }

        return $this->createPrimitiveInstance($type, $value, $extensions, 'xml', $context);
    }
}
