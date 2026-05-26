<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\EvidenceResource;

/**
 * @author Health Level Seven, Inc. - Clinical Decision Support Workgroup
 *
 * @see http://hl7.org/fhir/StructureDefinition/synthesis
 *
 * @description Explanation of what this profile contains/is for.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/synthesis', baseType: 'Evidence', fhirVersion: 'R4')]
#[FHIRProfileConstraint(
    path: 'url',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'version',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'title',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'shortTitle',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'subtitle',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'status',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'date',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'publisher',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'description',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'copyright',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'approvalDate',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'lastReviewDate',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'exposureBackground',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'exposureVariant',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 2],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
#[FHIRProfileConstraint(
    path: 'outcome',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/synthesis'],
)]
class EvidenceSynthesisProfile extends EvidenceResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/synthesis';
}
