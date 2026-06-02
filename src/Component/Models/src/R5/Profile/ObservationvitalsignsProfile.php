<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ObservationResource;

/**
 * @author Health Level Seven International (Orders and Observations Workgroup)
 *
 * @see http://hl7.org/fhir/StructureDefinition/vitalsigns
 *
 * @description FHIR Vital Signs Profile
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/vitalsigns', baseType: 'Observation', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'status',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'category',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'category',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'category.coding',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'category.coding.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'category.coding.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://terminology.hl7.org/CodeSystem/observation-category'],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'category.coding.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'category.coding.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'vital-signs'],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'subject',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'effective[x]',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'value[x]',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'dataAbsentReason',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'component.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'component.value[x]',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileConstraint(
    path: 'component.dataAbsentReason',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileMustSupport(path: 'status', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'category', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'category', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'category.coding', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'category.coding.system', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'category.coding.code', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'code', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'subject', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'effective[x]', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'value[x]', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'dataAbsentReason', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'component', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'component.code', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'component.value[x]', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'component.valueQuantity', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'component.dataAbsentReason', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRSlicingRules(property: 'category', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRSliceConstraint(
    property: 'category',
    sliceName: 'VSCat',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'coding.code',
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
    orderedIndex: 0,
    discriminatorValue: 'vital-signs',
)]
class ObservationvitalsignsProfile extends ObservationResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/vitalsigns';
}
