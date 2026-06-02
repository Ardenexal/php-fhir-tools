<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\EvidenceVariableResource;

/**
 * @author Health Level Seven, Inc. - Clinical Decision Support Workgroup
 *
 * @see http://hl7.org/fhir/StructureDefinition/picoelement
 *
 * @description Explanation of what this profile contains/is for.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/picoelement', baseType: 'EvidenceVariable', fhirVersion: 'R4')]
#[FHIRProfileConstraint(
    path: 'title',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/picoelement'],
)]
#[FHIRProfileConstraint(
    path: 'shortTitle',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/picoelement'],
)]
#[FHIRProfileConstraint(
    path: 'date',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/picoelement'],
)]
#[FHIRProfileConstraint(
    path: 'description',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/picoelement'],
)]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/picoelement'],
)]
#[FHIRProfileConstraint(
    path: 'characteristic',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/picoelement'],
)]
#[FHIRProfileConstraint(
    path: 'characteristic.description',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/picoelement'],
)]
#[FHIRProfileConstraint(
    path: 'characteristic.definition[x]',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/picoelement'],
)]
#[FHIRProfileConstraint(
    path: 'characteristic.exclude',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/picoelement'],
)]
#[FHIRProfileConstraint(
    path: 'characteristic.participantEffective[x]',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/picoelement'],
)]
#[FHIRProfileConstraint(
    path: 'characteristic.timeFromStart',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/picoelement'],
)]
#[FHIRProfileConstraint(
    path: 'characteristic.groupMeasure',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/picoelement'],
)]
class PICOelementProfile extends EvidenceVariableResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/picoelement';
}
