<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinitionResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/computableplandefinition
 *
 * @description Defines a computable PlanDefinition that specifies a single library and requires all expressions referenced from the PlanDefinition to be definitions in that single library
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/computableplandefinition',
    baseType: 'PlanDefinition',
    fhirVersion: 'R4',
)]
#[FHIRProfileConstraint(
    path: 'library',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/computableplandefinition'],
)]
class ComputablePlanDefinitionProfile extends PlanDefinitionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/computableplandefinition';
}
