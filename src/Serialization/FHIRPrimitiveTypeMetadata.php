<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

/**
 * Metadata container for FHIR primitive type information.
 *
 * @author Kiro AI Assistant
 */
readonly class FHIRPrimitiveTypeMetadata
{
    public function __construct(
        public string $primitiveType,
        public string $fhirVersion,
        public bool $supportsExtensions = true
    ) {
    }
}
