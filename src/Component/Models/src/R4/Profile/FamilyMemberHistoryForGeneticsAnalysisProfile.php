<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FamilyMemberHistoryResource;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic
 *
 * @description Adds additional information to a family member history supporting both the capture of mother/father relationships as well as additional observations necessary to enable genetics-based risk analysis for patients
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic',
    baseType: 'FamilyMemberHistory',
    fhirVersion: 'R4',
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'Parent',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-parent',
    orderedIndex: 0,
    groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'Sibling',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-sibling',
    orderedIndex: 1,
    groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'Observation',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-observation',
    orderedIndex: 2,
    groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'],
)]
class FamilyMemberHistoryForGeneticsAnalysisProfile extends FamilyMemberHistoryResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic';
}
