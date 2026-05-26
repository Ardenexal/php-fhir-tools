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
    path: 'extension',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'],
)]
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
    path: 'relatedArtifact.type',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'depends-on'],
    groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'],
)]
#[FHIRProfileConstraint(
    path: 'relatedArtifact.resource',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'],
)]
#[FHIRProfileConstraint(
    path: 'content',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'],
)]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRProfileMustSupport(path: 'subject[x]', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact.type', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact.resource', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRProfileMustSupport(path: 'parameter', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRProfileMustSupport(path: 'dataRequirement', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRSlicingRules(property: 'relatedArtifact', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'])]
#[FHIRSliceConstraint(
    property: 'relatedArtifact',
    sliceName: 'dependency',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'type',
    groups: ['http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary'],
    orderedIndex: 0,
    discriminatorValue: 'depends-on',
)]
class ModuleDefinitionLibraryProfile extends ShareableLibraryProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/moduledefinitionlibrary';
}
