<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Attributes;

/**
 * Attribute for FHIR backbone elements with parent resource information
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class FHIRBackboneElement
{
    /**
     * @param string $parentResource The parent FHIR resource type (e.g., 'Patient', 'Observation')
     * @param string $elementPath    The path to this element within the parent resource (e.g., 'Patient.contact')
     * @param string $fhirVersion    The FHIR version (default: 'R4B')
     */
    public function __construct(
        public readonly string $parentResource,
        public readonly string $elementPath,
        public readonly string $fhirVersion = 'R4B'
    ) {
    }
}
