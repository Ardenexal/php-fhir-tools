<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTemporalValue;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common\AbstractFHIRNormalizer;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * JSON normalizer for FHIR primitive types.
 *
 * @author Ardenexal
 */
class FHIRPrimitiveTypeJsonNormalizer extends AbstractFHIRNormalizer
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

        return $this->normalizeForJSON($object, self::reflClass($object));
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if ($format === 'xml') {
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
            return $this->denormalizeFromJSON($data, $type, $context);
        }

        throw new NotNormalizableValueException(sprintf('Cannot denormalize data of type %s to %s', gettype($data), $type));
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if ($format === 'xml') {
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
     */
    private function normalizeForJSON(object $object, \ReflectionClass $reflection): mixed
    {
        $value     = null;
        $valueProp = self::reflProp($object, 'value');

        if ($valueProp !== null && $valueProp->isInitialized($object)) {
            $value = $valueProp->getValue($object);
        }

        if ($value instanceof FHIRTemporalValue) {
            $value = (string) $value;
        }

        if (is_string($value) && is_numeric($value)) {
            $decimalAttr = $this->findFHIRPrimitiveAttribute(get_class($object));
            if ($decimalAttr !== null && $decimalAttr->primitiveType === 'decimal') {
                $value = (float) $value;
            }
        }

        // Extensions are emitted via underscore notation by the parent normalizer; return just the value
        return $value;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $context
     */
    private function denormalizeFromJSON(array $data, string $type, array $context): mixed
    {
        $value      = null;
        $extensions = null;

        if (isset($data['value'])) {
            $value = $data['value'];
        } elseif (count($data) === 1 && !isset($data['extension'])) {
            $value = reset($data);
        }

        if (isset($data['extension'])) {
            $extensions = $data['extension'];
        }

        return $this->createPrimitiveInstance($type, $value, $extensions, 'json', $context);
    }
}
