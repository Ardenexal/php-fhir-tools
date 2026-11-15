<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

/**
 * Metadata container for FHIR primitive type information.
 *
 * @author Ardenexal
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
