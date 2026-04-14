<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;

/**
 * @author Health Level Seven International (Orders and Observations Workgroup)
 *
 * @see http://hl7.org/fhir/StructureDefinition/headcircum
 *
 * @description FHIR Head Circumference Profile
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/headcircum', baseType: 'Observation', fhirVersion: 'R5')]
class ObservationheadcircumProfile extends ObservationvitalsignsProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/headcircum';
}
