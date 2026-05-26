<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;

/**
 * @author Health Level Seven International (Orders and Observations Workgroup)
 *
 * @see http://hl7.org/fhir/StructureDefinition/oxygensat
 *
 * @description FHIR Oxygen Saturation Profile
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/oxygensat', baseType: 'Observation', fhirVersion: 'R4B')]
#[FHIRProfileConstraint(
    path: 'code.coding',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://loinc.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => '2708-6'],
    groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.value',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.unit',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://unitsofmeasure.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => '%'],
    groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'],
)]
#[FHIRProfileMustSupport(path: 'valueQuantity.value', groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'])]
#[FHIRProfileMustSupport(path: 'valueQuantity.unit', groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'])]
#[FHIRProfileMustSupport(path: 'valueQuantity.system', groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'])]
#[FHIRProfileMustSupport(path: 'valueQuantity.code', groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'])]
#[FHIRSlicingRules(property: 'code.coding', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'])]
#[FHIRSliceConstraint(
    property: 'code.coding',
    sliceName: 'OxygenSatCode',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'code',
    groups: ['http://hl7.org/fhir/StructureDefinition/oxygensat'],
    orderedIndex: 0,
    discriminatorValue: '2708-6',
)]
class ObservationOxygensatProfile extends ObservationVitalsignsProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/oxygensat';
}
