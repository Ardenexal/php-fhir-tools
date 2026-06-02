<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FamilyMemberHistoryResource;

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
    fhirVersion: 'R5',
)]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'])]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'])]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'])]
#[FHIRProfileMustSupport(path: 'relationship', groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'])]
#[FHIRProfileMustSupport(path: 'sex', groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'])]
#[FHIRProfileMustSupport(path: 'born[x]', groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'])]
#[FHIRProfileMustSupport(path: 'age[x]', groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'])]
#[FHIRProfileMustSupport(path: 'deceased[x]', groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'])]
#[FHIRProfileMustSupport(path: 'condition', groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'])]
#[FHIRProfileMustSupport(path: 'condition.code', groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'])]
#[FHIRProfileMustSupport(path: 'condition.outcome', groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'])]
#[FHIRProfileMustSupport(path: 'condition.onset[x]', groups: ['http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic'])]
class FamilyMemberHistoryForGeneticsAnalysisProfile extends FamilyMemberHistoryResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/familymemberhistory-genetic';
}
