<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ObservationResource;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-genetics
 *
 * @description Describes how the observation resource is used to report structured genetic test results
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/observation-genetics', baseType: 'Observation', fhirVersion: 'R4B')]
class ObservationGeneticsProfile extends ObservationResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/observation-genetics';
}
