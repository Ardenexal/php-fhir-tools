<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ArtifactAssessmentResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/ebmrecommendation
 *
 * @description Represents justification for a recommendation
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/ebmrecommendation',
    baseType: 'ArtifactAssessment',
    fhirVersion: 'R5',
)]
class EBMRecommendationProfile extends ArtifactAssessmentResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/ebmrecommendation';
}
