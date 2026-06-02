<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\GroupResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/groupdefinition
 *
 * @description Enforces a descriptive group that can be used in definitional resources
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/groupdefinition', baseType: 'Group', fhirVersion: 'R4')]
#[FHIRProfileConstraint(
    path: 'actual',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/groupdefinition'],
)]
#[FHIRProfileConstraint(
    path: 'actual',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => false],
    groups: ['http://hl7.org/fhir/StructureDefinition/groupdefinition'],
)]
#[FHIRProfileConstraint(
    path: 'member',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/groupdefinition'],
)]
class GroupDefinitionProfile extends GroupResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/groupdefinition';
}
