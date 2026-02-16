<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

/**
 * Metadata container for FHIR resource information.
 *
 * @author Ardenexal
 */
readonly class FHIRResourceMetadata
{
    public function __construct(
        public string $resourceType,
        public string $fhirVersion,
        public ?string $profile = null
    ) {
    }
}
