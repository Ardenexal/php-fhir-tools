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
 * @see http://hl7.org/fhir/StructureDefinition/resprate
 *
 * @description FHIR Respiratory Rate Profile
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/resprate', baseType: 'Observation', fhirVersion: 'R4')]
#[FHIRProfileConstraint(
    path: 'valueQuantity.value',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/resprate'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.unit',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/resprate'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/resprate'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://unitsofmeasure.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/resprate'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/resprate'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => '/min'],
    groups: ['http://hl7.org/fhir/StructureDefinition/resprate'],
)]
#[FHIRProfileMustSupport(path: 'valueQuantity.value', groups: ['http://hl7.org/fhir/StructureDefinition/resprate'])]
#[FHIRProfileMustSupport(path: 'valueQuantity.unit', groups: ['http://hl7.org/fhir/StructureDefinition/resprate'])]
#[FHIRProfileMustSupport(path: 'valueQuantity.system', groups: ['http://hl7.org/fhir/StructureDefinition/resprate'])]
#[FHIRProfileMustSupport(path: 'valueQuantity.code', groups: ['http://hl7.org/fhir/StructureDefinition/resprate'])]
#[FHIRSlicingRules(property: 'code.coding', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/resprate'])]
#[FHIRSliceConstraint(
    property: 'code.coding',
    sliceName: 'RespRateCode',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'code',
    discriminatorValue: '9279-1',
    orderedIndex: 0,
    rules: [
        [
            'path'       => 'system',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
        [
            'path'       => 'system',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'http://loinc.org'],
        ],
        [
            'path'       => 'code',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
        [
            'path'       => 'code',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => '9279-1'],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/resprate'],
)]
class ObservationResprateProfile extends ObservationVitalsignsProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/resprate';
}
