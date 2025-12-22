<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

/**
 * Metadata container for FHIR backbone element information.
 *
 * @author Kiro AI Assistant
 */
readonly class FHIRBackboneElementMetadata
{
    public function __construct(
        public string $parentResource,
        public string $elementPath,
        public string $fhirVersion
    ) {
    }
}
