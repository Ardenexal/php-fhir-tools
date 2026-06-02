<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;

/**
 * @author Health Level Seven International (Orders and Observations Workgroup)
 *
 * @see http://hl7.org/fhir/StructureDefinition/bmi
 *
 * @description FHIR Body Mass Index (BMI) Profile
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/bmi', baseType: 'Observation', fhirVersion: 'R4')]
#[FHIRProfileConstraint(
    path: 'code.coding',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://loinc.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => '39156-5'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.value',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.unit',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://unitsofmeasure.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'kg/m2'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
)]
#[FHIRProfileMustSupport(path: 'valueQuantity.value', groups: ['http://hl7.org/fhir/StructureDefinition/bmi'])]
#[FHIRProfileMustSupport(path: 'valueQuantity.unit', groups: ['http://hl7.org/fhir/StructureDefinition/bmi'])]
#[FHIRProfileMustSupport(path: 'valueQuantity.system', groups: ['http://hl7.org/fhir/StructureDefinition/bmi'])]
#[FHIRProfileMustSupport(path: 'valueQuantity.code', groups: ['http://hl7.org/fhir/StructureDefinition/bmi'])]
#[FHIRSlicingRules(property: 'code.coding', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/bmi'])]
#[FHIRSliceConstraint(
    property: 'code.coding',
    sliceName: 'BMICode',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'code',
    groups: ['http://hl7.org/fhir/StructureDefinition/bmi'],
    orderedIndex: 0,
    discriminatorValue: '39156-5',
)]
class ObservationBmiProfile extends ObservationVitalsignsProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/bmi';
}
