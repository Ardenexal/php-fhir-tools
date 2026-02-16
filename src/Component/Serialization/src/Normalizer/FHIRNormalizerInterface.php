<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Interface for FHIR-specific normalizers that handle serialization and deserialization
 * of FHIR objects following the official FHIR JSON and XML specifications.
 *
 * @author Ardenexal
 */
interface FHIRNormalizerInterface extends NormalizerInterface, DenormalizerInterface
{
    /**
     * Checks whether the given data can be normalized by this normalizer.
     *
     * @param mixed                $data    The data to normalize
     * @param string|null          $format  The format being (de)serialized from or into
     * @param array<string, mixed> $context Options available to the normalizer
     *
     * @return bool True if this normalizer can handle the data, false otherwise
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool;

    /**
     * Checks whether the given data can be denormalized by this normalizer.
     *
     * @param mixed                $data    The data to denormalize
     * @param string               $type    The expected type of the denormalized data
     * @param string|null          $format  The format being deserialized from
     * @param array<string, mixed> $context Options available to the denormalizer
     *
     * @return bool True if this normalizer can handle the data, false otherwise
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool;

    /**
     * Gets the types supported by this normalizer.
     *
     * @param string|null $format The format being (de)serialized from or into
     *
     * @return array<string, bool> An array of supported types indexed by type name
     */
    public function getSupportedTypes(?string $format): array;
}
