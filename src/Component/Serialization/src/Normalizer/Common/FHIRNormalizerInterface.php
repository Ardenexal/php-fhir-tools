<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common;

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
     * @param array<string, mixed> $context
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool;

    /**
     * @param array<string, mixed> $context
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool;

    /**
     * @return array<string, bool>
     */
    public function getSupportedTypes(?string $format): array;
}
