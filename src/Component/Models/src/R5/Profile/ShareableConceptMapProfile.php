<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ConceptMapResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/shareableconceptmap
 *
 * @description Enforces the minimum information set for the concept map metadata required by HL7 and other organizations that share and publish concept maps
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/shareableconceptmap', baseType: 'ConceptMap', fhirVersion: 'R5')]
class ShareableConceptMapProfile extends ConceptMapResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareableconceptmap';
}
