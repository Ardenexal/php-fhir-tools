<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;

/**
 * @author Health Level Seven International (Orders and Observations Workgroup)
 *
 * @see http://hl7.org/fhir/StructureDefinition/bmi
 *
 * @description FHIR Body Mass Index (BMI) Profile
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/bmi', baseType: 'Observation', fhirVersion: 'R4')]
class ObservationBmiProfile extends ObservationVitalsignsProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/bmi';
}
