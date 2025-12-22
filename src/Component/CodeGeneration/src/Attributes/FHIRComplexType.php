<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Attributes;

/**
 * Attribute for FHIR complex type classes with type name information
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class FHIRComplexType
{
    /**
     * @param string $typeName    The FHIR complex type name (e.g., 'Address', 'HumanName')
     * @param string $fhirVersion The FHIR version (default: 'R4B')
     */
    public function __construct(
        public readonly string $typeName,
        public readonly string $fhirVersion = 'R4B'
    ) {
    }
}
