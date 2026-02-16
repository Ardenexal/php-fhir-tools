<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

/**
 * Metadata container for FHIR complex type information.
 *
 * @author Ardenexal
 */
readonly class FHIRComplexTypeMetadata
{
    public function __construct(
        public string $typeName,
        public string $fhirVersion
    ) {
    }
}
