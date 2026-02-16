<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Attributes;

/**
 * Attribute for FHIR primitive types
 *
 * All FHIR primitive types support extensions according to the FHIR specification,
 * so extension support is implicit and doesn't need to be specified.
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class FHIRPrimitive
{
    /**
     * @param string $primitiveType The FHIR primitive type name (e.g., 'string', 'integer', 'boolean')
     * @param string $fhirVersion   The FHIR version (default: 'R4B')
     */
    public function __construct(
        public readonly string $primitiveType,
        public readonly string $fhirVersion = 'R4B'
    ) {
    }

    /**
     * All FHIR primitive types support extensions according to the FHIR specification
     */
    public function supportsExtensions(): bool
    {
        return true;
    }
}
