<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\GroupResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/actualgroup
 *
 * @description Enforces an actual group, rather than a definitional group
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/actualgroup', baseType: 'Group', fhirVersion: 'R4')]
#[FHIRProfileConstraint(
    path: 'actual',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/actualgroup'],
)]
#[FHIRProfileConstraint(
    path: 'actual',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => true],
    groups: ['http://hl7.org/fhir/StructureDefinition/actualgroup'],
)]
#[FHIRProfileConstraint(
    path: 'characteristic',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/actualgroup'],
)]
class ActualGroupProfile extends GroupResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/actualgroup';
}
