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
 * @see http://hl7.org/fhir/StructureDefinition/logiclibrary
 *
 * @description The logic library profile sets the minimum expectations for computable and/or executable libraries, including support for terminology and dependency declaration, parameters, and data requirements
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/logiclibrary', baseType: 'Library', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'],
)]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
    options: [
        'pattern' => [
            'coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/library-type', 'code' => 'module-definition']],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'],
)]
#[FHIRProfileConstraint(
    path: 'relatedArtifact.type',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'depends-on'],
    groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'],
)]
#[FHIRProfileConstraint(
    path: 'relatedArtifact.resource',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'],
)]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'])]
#[FHIRProfileMustSupport(path: 'subject[x]', groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact', groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact', groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact.type', groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact.resource', groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'])]
#[FHIRProfileMustSupport(path: 'parameter', groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'])]
#[FHIRProfileMustSupport(path: 'dataRequirement', groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'])]
#[FHIRProfileMustSupport(path: 'content', groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'])]
#[FHIRProfileMustSupport(path: 'content.contentType', groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'])]
#[FHIRProfileMustSupport(path: 'content.data', groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'])]
#[FHIRSlicingRules(property: 'relatedArtifact', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'])]
#[FHIRSliceConstraint(
    property: 'relatedArtifact',
    sliceName: 'dependency',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'type',
    groups: ['http://hl7.org/fhir/StructureDefinition/logiclibrary'],
    orderedIndex: 0,
    discriminatorValue: 'depends-on',
)]
class LogicLibraryProfile extends ShareableLibraryProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/logiclibrary';
}
