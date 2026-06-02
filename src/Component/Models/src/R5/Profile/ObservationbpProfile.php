<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;

/**
 * @author Health Level Seven International (Orders and Observations Workgroup)
 *
 * @see http://hl7.org/fhir/StructureDefinition/bp
 *
 * @description FHIR Blood Pressure Profile
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/bp', baseType: 'Observation', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'code.coding',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://loinc.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => '85354-9'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 2],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.code.coding',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.code.coding.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.code.coding.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://loinc.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.code.coding.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.code.coding.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => '8480-6'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity.value',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity.unit',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://unitsofmeasure.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'mm[Hg]'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.code.coding',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.code.coding.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.code.coding.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://loinc.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.code.coding.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.code.coding.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => '8462-4'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity.value',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity.unit',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://unitsofmeasure.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileConstraint(
    path: 'component.valueQuantity.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'mm[Hg]'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRProfileMustSupport(path: 'component.valueQuantity.value', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRProfileMustSupport(path: 'component.valueQuantity.unit', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRProfileMustSupport(path: 'component.valueQuantity.system', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRProfileMustSupport(path: 'component.valueQuantity.code', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRProfileMustSupport(path: 'component.valueQuantity.value', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRProfileMustSupport(path: 'component.valueQuantity.unit', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRProfileMustSupport(path: 'component.valueQuantity.system', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRProfileMustSupport(path: 'component.valueQuantity.code', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRSlicingRules(property: 'code.coding', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRSliceConstraint(
    property: 'code.coding',
    sliceName: 'BPCode',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'code',
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
    orderedIndex: 0,
    discriminatorValue: '85354-9',
)]
#[FHIRSlicingRules(property: 'component', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRSliceConstraint(
    property: 'component',
    sliceName: 'SystolicBP',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'code.coding.code',
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
    orderedIndex: 0,
    discriminatorValue: '8480-6',
)]
#[FHIRSliceConstraint(
    property: 'component',
    sliceName: 'DiastolicBP',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'code.coding.code',
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
    orderedIndex: 1,
    discriminatorValue: '8480-6',
)]
#[FHIRSlicingRules(property: 'component.code.coding', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRSliceConstraint(
    property: 'component.code.coding',
    sliceName: 'SBPCode',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'code',
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
    orderedIndex: 0,
    discriminatorValue: '8480-6',
)]
#[FHIRSliceConstraint(
    property: 'component.code.coding',
    sliceName: 'DBPCode',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'code',
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
    orderedIndex: 1,
    discriminatorValue: '8480-6',
)]
class ObservationbpProfile extends ObservationvitalsignsProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/bp';
}
