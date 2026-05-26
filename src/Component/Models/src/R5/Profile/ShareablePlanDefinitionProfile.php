<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\PlanDefinitionResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/shareableplandefinition
 *
 * @description Enforces the minimum information set for the plan definition metadata required by HL7 and other organizations that share and publish plan definitions
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/shareableplandefinition',
    baseType: 'PlanDefinition',
    fhirVersion: 'R5',
)]
#[FHIRProfileConstraint(
    path: 'url',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'],
)]
#[FHIRProfileConstraint(
    path: 'version',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'],
)]
#[FHIRProfileConstraint(
    path: 'title',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'],
)]
#[FHIRProfileConstraint(
    path: 'description',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'],
)]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'])]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'])]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'])]
#[FHIRProfileMustSupport(path: 'url', groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'])]
#[FHIRProfileMustSupport(path: 'version', groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'])]
#[FHIRProfileMustSupport(path: 'versionAlgorithm[x]', groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'])]
#[FHIRProfileMustSupport(path: 'name', groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'])]
#[FHIRProfileMustSupport(path: 'title', groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'])]
#[FHIRProfileMustSupport(path: 'experimental', groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'])]
#[FHIRProfileMustSupport(path: 'publisher', groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'])]
#[FHIRProfileMustSupport(path: 'description', groups: ['http://hl7.org/fhir/StructureDefinition/shareableplandefinition'])]
class ShareablePlanDefinitionProfile extends PlanDefinitionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareableplandefinition';
}
