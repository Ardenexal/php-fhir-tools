<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json;

use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common\AbstractOperationPayloadNormalizer;

/**
 * JSON normalizer for generated FHIR operation payload classes.
 *
 * Handles the `…Input` / `…Output` classes emitted per OperationDefinition, converting them to and
 * from a `Parameters` resource so the sibling FHIR normalizers can render it. See
 * {@see AbstractOperationPayloadNormalizer} for why payload classes need their own normalizer at all
 * — briefly, they carry no `#[FhirResource]`, so without this a framework falls through to
 * `ObjectNormalizer` and silently produces an object with every property null.
 *
 * @author Ardenexal
 */
class FHIROperationPayloadJsonNormalizer extends AbstractOperationPayloadNormalizer
{
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if ($format === 'xml') {
            return false;
        }

        return $this->claims($data);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if ($format === 'xml') {
            return false;
        }

        return $this->claims($type);
    }
}
