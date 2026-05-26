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
 * @see http://hl7.org/fhir/StructureDefinition/bodyheight
 *
 * @description FHIR Body Height Profile
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/bodyheight', baseType: 'Observation', fhirVersion: 'R4')]
#[FHIRProfileConstraint(
    path: 'code.coding',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://loinc.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => '8302-2'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.value',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.unit',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://unitsofmeasure.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'],
)]
#[FHIRProfileMustSupport(path: 'valueQuantity.value', groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'])]
#[FHIRProfileMustSupport(path: 'valueQuantity.unit', groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'])]
#[FHIRProfileMustSupport(path: 'valueQuantity.system', groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'])]
#[FHIRProfileMustSupport(path: 'valueQuantity.code', groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'])]
#[FHIRSlicingRules(property: 'code.coding', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'])]
#[FHIRSliceConstraint(
    property: 'code.coding',
    sliceName: 'BodyHeightCode',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'code',
    groups: ['http://hl7.org/fhir/StructureDefinition/bodyheight'],
    orderedIndex: 0,
    discriminatorValue: '8302-2',
)]
class ObservationBodyheightProfile extends ObservationVitalsignsProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/bodyheight';
}
