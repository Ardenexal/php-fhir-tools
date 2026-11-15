<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;

/**
 * @author Health Level Seven International (Orders and Observations Workgroup)
 *
 * @see http://hl7.org/fhir/StructureDefinition/bp
 *
 * @description FHIR Blood Pressure Profile
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/bp', baseType: 'Observation', fhirVersion: 'R4B')]
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
#[FHIRSlicingRules(property: 'code.coding', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRSliceConstraint(
    property: 'code.coding',
    sliceName: 'BPCode',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'code',
    discriminatorValue: '85354-9',
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
            'options'    => ['value' => '85354-9'],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRSlicingRules(property: 'component', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/bp'])]
#[FHIRSliceConstraint(
    property: 'component',
    sliceName: 'SystolicBP',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'code.coding.code',
    discriminatorValue: '8480-6',
    orderedIndex: 0,
    rules: [
        [
            'path'       => 'valueQuantity.value',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
        [
            'path'       => 'valueQuantity.unit',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
        [
            'path'       => 'valueQuantity.system',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
        [
            'path'       => 'valueQuantity.system',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'http://unitsofmeasure.org'],
        ],
        [
            'path'       => 'valueQuantity.code',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
        [
            'path'       => 'valueQuantity.code',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'mm[Hg]'],
        ],
        [
            'path'       => 'code.coding',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules',
            'options'    => ['property' => 'code.coding', 'rules' => 'open'],
        ],
        [
            'path'       => 'code.coding',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint',
            'options'    => [
                'property'           => 'code.coding',
                'sliceName'          => 'SBPCode',
                'min'                => 1,
                'max'                => 1,
                'discriminatorType'  => 'value',
                'discriminatorPath'  => 'code',
                'discriminatorValue' => '8480-6',
                'orderedIndex'       => 0,
                'rules'              => [
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
                        'options'    => ['value' => '8480-6'],
                    ],
                ],
            ],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
#[FHIRSliceConstraint(
    property: 'component',
    sliceName: 'DiastolicBP',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'code.coding.code',
    discriminatorValue: '8462-4',
    orderedIndex: 1,
    rules: [
        [
            'path'       => 'valueQuantity.value',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
        [
            'path'       => 'valueQuantity.unit',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
        [
            'path'       => 'valueQuantity.system',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
        [
            'path'       => 'valueQuantity.system',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'http://unitsofmeasure.org'],
        ],
        [
            'path'       => 'valueQuantity.code',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
        [
            'path'       => 'valueQuantity.code',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'mm[Hg]'],
        ],
        [
            'path'       => 'code.coding',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules',
            'options'    => ['property' => 'code.coding', 'rules' => 'open'],
        ],
        [
            'path'       => 'code.coding',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint',
            'options'    => [
                'property'           => 'code.coding',
                'sliceName'          => 'DBPCode',
                'min'                => 1,
                'max'                => 1,
                'discriminatorType'  => 'value',
                'discriminatorPath'  => 'code',
                'discriminatorValue' => '8462-4',
                'orderedIndex'       => 0,
                'rules'              => [
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
                        'options'    => ['value' => '8462-4'],
                    ],
                ],
            ],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/bp'],
)]
class ObservationBpProfile extends ObservationVitalsignsProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/bp';
}
