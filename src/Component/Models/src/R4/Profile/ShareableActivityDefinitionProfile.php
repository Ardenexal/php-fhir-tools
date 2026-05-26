<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ActivityDefinitionResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition
 *
 * @description Enforces the minimum information set for the activity definition metadata required by HL7 and other organizations that share and publish activity definitions
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition',
    baseType: 'ActivityDefinition',
    fhirVersion: 'R4',
)]
#[FHIRProfileConstraint(
    path: 'url',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'],
)]
#[FHIRProfileConstraint(
    path: 'version',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'],
)]
#[FHIRProfileConstraint(
    path: 'name',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'],
)]
#[FHIRProfileConstraint(
    path: 'title',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'],
)]
#[FHIRProfileConstraint(
    path: 'experimental',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'],
)]
#[FHIRProfileConstraint(
    path: 'date',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'],
)]
#[FHIRProfileConstraint(
    path: 'publisher',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'],
)]
#[FHIRProfileConstraint(
    path: 'description',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'],
)]
#[FHIRProfileMustSupport(path: 'identifier', groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'title', groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'date', groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'contact', groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'useContext', groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'jurisdiction', groups: ['http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition'])]
class ShareableActivityDefinitionProfile extends ActivityDefinitionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition';
}
