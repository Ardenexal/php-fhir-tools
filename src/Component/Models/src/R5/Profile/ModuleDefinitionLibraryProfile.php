<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;

/**
 * @author Health Level Seven, Inc. - CDS WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary
 *
 * @description The module definition library profile sets the expectations for module definition libraries, including support for terminology and dependency declaration, parameters, and data requirements
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary', baseType: 'Library', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'],
)]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
    options: [
        'pattern' => [
            'coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/library-type', 'code' => 'logic-library']],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'],
)]
#[FHIRProfileConstraint(
    path: 'content',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'],
)]
#[FHIRProfileMustSupport(path: 'subject[x]', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRProfileMustSupport(path: 'parameter', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRProfileMustSupport(path: 'dataRequirement', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'inputParameters',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/cqf-inputParameters',
    orderedIndex: 0,
    groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'directReferenceCode',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/cqf-directReferenceCode',
    orderedIndex: 1,
    groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'logicDefinition',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/cqf-logicDefinition',
    orderedIndex: 2,
    groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'],
)]
#[FHIRSlicingRules(property: 'relatedArtifact', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRSliceConstraint(
    property: 'relatedArtifact',
    sliceName: 'dependency',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'type',
    discriminatorValue: 'depends-on',
    orderedIndex: 0,
    rules: [
        [
            'path'       => 'type',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'depends-on'],
        ],
        [
            'path'       => 'resource',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'],
)]
class ModuleDefinitionLibraryProfile extends ShareableLibraryProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary';
}
