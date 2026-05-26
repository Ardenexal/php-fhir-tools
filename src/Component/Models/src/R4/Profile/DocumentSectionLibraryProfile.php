<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CompositionResource;

/**
 * @see http://hl7.org/fhir/StructureDefinition/example-section-library
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/example-section-library',
    baseType: 'Composition',
    fhirVersion: 'R4',
)]
#[FHIRProfileConstraint(
    path: 'section.title',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
)]
#[FHIRProfileConstraint(
    path: 'section.title',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'Procedures Performed'],
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
)]
#[FHIRProfileConstraint(
    path: 'section.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
)]
#[FHIRProfileConstraint(
    path: 'section.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
    options: [
        'pattern' => [
            'coding' => [['system' => 'http://loinc.org', 'code' => '29554-3', 'display' => 'Procedure Narrative']],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
)]
#[FHIRProfileConstraint(
    path: 'section.title',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
)]
#[FHIRProfileConstraint(
    path: 'section.title',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'Medications Administered'],
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
)]
#[FHIRProfileConstraint(
    path: 'section.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
)]
#[FHIRProfileConstraint(
    path: 'section.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
    options: [
        'pattern' => [
            'coding' => [
                ['system' => 'http://loinc.org', 'code' => '29549-3', 'display' => 'Medication administered Narrative'],
            ],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
)]
#[FHIRProfileConstraint(
    path: 'section.title',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
)]
#[FHIRProfileConstraint(
    path: 'section.title',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'Discharge Treatment Plan'],
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
)]
#[FHIRProfileConstraint(
    path: 'section.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
)]
#[FHIRProfileConstraint(
    path: 'section.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
    options: [
        'pattern' => [
            'coding' => [
                ['system' => 'http://loinc.org', 'code' => '18776-5', 'display' => 'Plan of treatment (narrative)'],
            ],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
)]
#[FHIRSlicingRules(property: 'section', rules: 'closed', groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'])]
#[FHIRSliceConstraint(
    property: 'section',
    sliceName: 'procedure',
    min: 0,
    max: '*',
    discriminatorType: 'pattern',
    discriminatorPath: 'code',
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
    orderedIndex: 0,
    discriminatorValue: [
        'coding' => [['system' => 'http://loinc.org', 'code' => '29554-3', 'display' => 'Procedure Narrative']],
    ],
)]
#[FHIRSliceConstraint(
    property: 'section',
    sliceName: 'medications',
    min: 0,
    max: '*',
    discriminatorType: 'pattern',
    discriminatorPath: 'code',
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
    orderedIndex: 1,
    discriminatorValue: [
        'coding' => [['system' => 'http://loinc.org', 'code' => '29554-3', 'display' => 'Procedure Narrative']],
    ],
)]
#[FHIRSliceConstraint(
    property: 'section',
    sliceName: 'plan',
    min: 0,
    max: '*',
    discriminatorType: 'pattern',
    discriminatorPath: 'code',
    groups: ['http://hl7.org/fhir/StructureDefinition/example-section-library'],
    orderedIndex: 2,
    discriminatorValue: [
        'coding' => [['system' => 'http://loinc.org', 'code' => '29554-3', 'display' => 'Procedure Narrative']],
    ],
)]
class DocumentSectionLibraryProfile extends CompositionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/example-section-library';
}
