<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

/**
 * Metadata container for FHIR complex type information.
 *
 * @author Kiro AI Assistant
 */
readonly class FHIRComplexTypeMetadata
{
    public function __construct(
        public string $typeName,
        public string $fhirVersion
    ) {
    }
}
