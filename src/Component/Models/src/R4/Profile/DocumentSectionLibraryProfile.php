<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
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
    rules: [
        ['path' => 'title', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        [
            'path'       => 'title',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'Procedures Performed'],
        ],
        ['path' => 'code', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        [
            'path'       => 'code',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
            'options'    => [
                'pattern' => [
                    'coding' => [['system' => 'http://loinc.org', 'code' => '29554-3', 'display' => 'Procedure Narrative']],
                ],
            ],
        ],
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
        'coding' => [
            ['system' => 'http://loinc.org', 'code' => '29549-3', 'display' => 'Medication administered Narrative'],
        ],
    ],
    rules: [
        ['path' => 'title', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        [
            'path'       => 'title',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'Medications Administered'],
        ],
        ['path' => 'code', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        [
            'path'       => 'code',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
            'options'    => [
                'pattern' => [
                    'coding' => [
                        [
                            'system'  => 'http://loinc.org',
                            'code'    => '29549-3',
                            'display' => 'Medication administered Narrative',
                        ],
                    ],
                ],
            ],
        ],
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
        'coding' => [['system' => 'http://loinc.org', 'code' => '18776-5', 'display' => 'Plan of treatment (narrative)']],
    ],
    rules: [
        ['path' => 'title', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        [
            'path'       => 'title',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'Discharge Treatment Plan'],
        ],
        ['path' => 'code', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        [
            'path'       => 'code',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
            'options'    => [
                'pattern' => [
                    'coding' => [
                        ['system' => 'http://loinc.org', 'code' => '18776-5', 'display' => 'Plan of treatment (narrative)'],
                    ],
                ],
            ],
        ],
    ],
)]
class DocumentSectionLibraryProfile extends CompositionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/example-section-library';
}
