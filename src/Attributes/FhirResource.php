<?php

namespace Ardenexal\FHIRTools\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class FhirResource
{
    /**
     * @param string $type        The type of FHIR resource (e.g., 'CodeSystem', 'ValueSet')
     * @param string $version     The version of the resource (e.g., '4.0.1')
     * @param string $url         The canonical URL of the FHIR resource
     * @param string $fhirVersion The FHIR version (e.g., 'R4', 'R5')
     */
    public function __construct(
        public string $type,
        public string $version,
        public string $url,
        public string $fhirVersion
    ) {
    }
}
