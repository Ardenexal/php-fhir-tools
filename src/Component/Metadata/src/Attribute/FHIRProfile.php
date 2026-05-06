<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute;

/**
 * Attribute for typed FHIR profile classes generated from constraint StructureDefinitions.
 *
 * Applied by the IG generator to classes that represent a constrained FHIR resource or
 * complex type profile (i.e., a StructureDefinition with derivation=constraint and a kind
 * of "resource" or "complex-type"). Each such class subclasses the base resource or type,
 * carrying the profile URL as a constant.
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class FHIRProfile
{
    /**
     * @param string $profileUrl  The canonical URL of this profile's StructureDefinition
     * @param string $baseType    The FHIR type being constrained (e.g., 'Patient', 'Observation')
     * @param string $fhirVersion The FHIR version (e.g., 'R4', 'R4B', 'R5')
     */
    public function __construct(
        public readonly string $profileUrl,
        public readonly string $baseType,
        public readonly string $fhirVersion,
    ) {
    }
}
