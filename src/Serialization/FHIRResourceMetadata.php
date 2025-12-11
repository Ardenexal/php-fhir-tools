<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

/**
 * Metadata container for FHIR resource information.
 *
 * @author Kiro AI Assistant
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
