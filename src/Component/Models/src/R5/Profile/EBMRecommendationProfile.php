<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
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
#[FHIRProfileConstraint(
    path: 'citeAs',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/ebmrecommendation'],
)]
#[FHIRProfileConstraint(
    path: 'artifact',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/ebmrecommendation'],
)]
#[FHIRProfileConstraint(
    path: 'workflowStatus',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/ebmrecommendation'],
)]
#[FHIRProfileConstraint(
    path: 'disposition',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/ebmrecommendation'],
)]
#[FHIRProfileMustSupport(path: 'artifact', groups: ['http://hl7.org/fhir/StructureDefinition/ebmrecommendation'])]
class EBMRecommendationProfile extends ArtifactAssessmentResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/ebmrecommendation';
}
