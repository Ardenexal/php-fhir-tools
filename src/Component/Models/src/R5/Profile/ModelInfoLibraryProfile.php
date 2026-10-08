<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/modelinfolibrary
 *
 * @description Represents a computable representation of a model information library
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/modelinfolibrary', baseType: 'Library', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/modelinfolibrary'],
)]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
    options: [
        'pattern' => [
            'coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/library-type', 'code' => 'model-definition']],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/modelinfolibrary'],
)]
#[FHIRProfileMustSupport(path: 'content', groups: ['http://hl7.org/fhir/StructureDefinition/modelinfolibrary'])]
#[FHIRSlicingRules(property: 'content', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/modelinfolibrary'])]
#[FHIRSliceConstraint(
    property: 'content',
    sliceName: 'modelInfoXmlContent',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'contentType',
    discriminatorValue: 'application/xml',
    orderedIndex: 0,
    rules: [
        [
            'path'       => 'contentType',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['max' => 1],
        ],
        [
            'path'       => 'contentType',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'application/xml'],
        ],
        [
            'path'       => 'data',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/modelinfolibrary'],
)]
#[FHIRSliceConstraint(
    property: 'content',
    sliceName: 'modelInfoJsonContent',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'contentType',
    discriminatorValue: 'application/json',
    orderedIndex: 1,
    rules: [
        [
            'path'       => 'contentType',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['max' => 1],
        ],
        [
            'path'       => 'contentType',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'application/json'],
        ],
        [
            'path'       => 'data',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/modelinfolibrary'],
)]
class ModelInfoLibraryProfile extends ShareableLibraryProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/modelinfolibrary';
}
