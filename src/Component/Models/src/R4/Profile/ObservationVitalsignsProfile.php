<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource;

/**
 * @author Health Level Seven International (Orders and Observations Workgroup)
 *
 * @see http://hl7.org/fhir/StructureDefinition/vitalsigns
 *
 * @description FHIR Vital Signs Profile
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/vitalsigns', baseType: 'Observation', fhirVersion: 'R4')]
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
    path: 'component.dataAbsentReason',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileMustSupport(path: 'status', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'category', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'code', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'subject', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'effective[x]', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'value[x]', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'dataAbsentReason', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'component', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'component.code', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'component.value[x]', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRProfileMustSupport(path: 'component.dataAbsentReason', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRSlicingRules(property: 'category', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'])]
#[FHIRSliceConstraint(
    property: 'category',
    sliceName: 'VSCat',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'coding.code',
    discriminatorValue: 'vital-signs',
    orderedIndex: 0,
    rules: [
        ['path' => 'coding', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        [
            'path'       => 'coding.system',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
        [
            'path'       => 'coding.system',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'http://terminology.hl7.org/CodeSystem/observation-category'],
        ],
        [
            'path'       => 'coding.code',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
        [
            'path'       => 'coding.code',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'vital-signs'],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileInvariant(
    path: '',
    key: 'vs-2',
    severity: 'error',
    expression: '(component.empty() and hasMember.empty()) implies (dataAbsentReason.exists() or value.exists())',
    human: 'If there is no component or hasMember element then either a value[x] or a data absent reason must be present.',
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileInvariant(
    path: 'effective[x]',
    key: 'vs-1',
    severity: 'error',
    expression: '($this as dateTime).toString().length() >= 8',
    human: 'if Observation.effective[x] is dateTime and has a value then that value shall be precise to the day',
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileInvariant(
    path: 'component',
    key: 'vs-3',
    severity: 'error',
    expression: 'value.exists() or dataAbsentReason.exists()',
    human: 'If there is no a value a data absent reason must be present',
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileBinding(
    path: 'code',
    valueSetUrl: 'http://hl7.org/fhir/ValueSet/observation-vitalsignresult',
    strength: 'extensible',
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileBinding(
    path: 'component.code',
    valueSetUrl: 'http://hl7.org/fhir/ValueSet/observation-vitalsignresult',
    strength: 'extensible',
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
#[FHIRProfileBinding(
    path: 'component.value[x]',
    valueSetUrl: 'http://hl7.org/fhir/ValueSet/ucum-vitals-common|4.0.1',
    strength: 'required',
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalsigns'],
)]
class ObservationVitalsignsProfile extends ObservationResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/vitalsigns';
}
