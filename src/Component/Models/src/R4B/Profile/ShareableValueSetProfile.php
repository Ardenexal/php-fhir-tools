<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ValueSetResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/shareablevalueset
 *
 * @description Enforces the minimum information set for the value set metadata required by HL7 and other organizations that share and publish value sets
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/shareablevalueset', baseType: 'ValueSet', fhirVersion: 'R4B')]
#[FHIRProfileConstraint(
    path: 'url',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'version',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'name',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'status',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'experimental',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'publisher',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'description',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablevalueset'],
)]
class ShareableValueSetProfile extends ValueSetResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareablevalueset';
}
