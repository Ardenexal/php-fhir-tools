<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Attributes;

/**
 * Unified attribute for FHIR resource classes containing metadata for both generation and serialization
 *
 * This attribute serves dual purposes:
 * 1. Code generation metadata (type, version, url, fhirVersion)
 * 2. Serialization metadata (resourceType via type, profile via url)
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class FhirResource
{
    /**
     * @param string      $type        The FHIR resource type (e.g., 'Patient', 'Observation', 'CodeSystem')
     * @param string      $version     The version of the resource (e.g., '4.0.1')
     * @param string      $url         The canonical URL of the FHIR resource or profile URL
     * @param string      $fhirVersion The FHIR version (e.g., 'R4', 'R5', 'R4B')
     * @param string|null $profile     Optional additional profile URL (when url is canonical URL)
     */
    public function __construct(
        public readonly string $type,
        public readonly string $version,
        public readonly string $url,
        public readonly string $fhirVersion,
        public readonly ?string $profile = null
    ) {
    }

    /**
     * Get the resource type for serialization purposes
     * This is an alias for the type property to maintain compatibility with serialization code
     */
    public function getResourceType(): string
    {
        return $this->type;
    }

    /**
     * Get the profile URL for serialization purposes
     * Returns the profile if set, otherwise falls back to url
     */
    public function getProfile(): ?string
    {
        return $this->profile ?? $this->url;
    }
}
