<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml;

use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common\AbstractOperationPayloadNormalizer;

/**
 * XML normalizer for generated FHIR operation payload classes.
 *
 * Identical in behaviour to its JSON sibling and differing only in the format it accepts — the
 * shared base maps the payload to a `Parameters` resource and delegates rendering, so neither
 * subclass contains any format-specific logic. That is the same reason M01 found D2's "JSON and XML
 * come free" claim held: the mapper hands over a real resource and formats nothing itself.
 *
 * See {@see AbstractOperationPayloadNormalizer} for why payload classes need a normalizer of their
 * own.
 *
 * @author Ardenexal
 */
class FHIROperationPayloadXmlNormalizer extends AbstractOperationPayloadNormalizer
{
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if ($format !== 'xml') {
            return false;
        }

        return $this->claims($data);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if ($format !== 'xml') {
            return false;
        }

        return $this->claims($type);
    }
}
