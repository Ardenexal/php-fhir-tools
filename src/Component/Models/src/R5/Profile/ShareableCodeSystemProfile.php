<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\CodeSystemResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/shareablecodesystem
 *
 * @description Enforces the minimum information set for the code system metadata required by HL7 and other organizations that share and publish code systems
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/shareablecodesystem', baseType: 'CodeSystem', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'url',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'],
)]
#[FHIRProfileConstraint(
    path: 'version',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'],
)]
#[FHIRProfileConstraint(
    path: 'title',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'],
)]
#[FHIRProfileConstraint(
    path: 'experimental',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'],
)]
#[FHIRProfileConstraint(
    path: 'description',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'],
)]
#[FHIRProfileConstraint(
    path: 'caseSensitive',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'],
)]
#[FHIRProfileConstraint(
    path: 'concept.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'],
)]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'url', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'version', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'versionAlgorithm[x]', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'name', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'title', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'status', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'experimental', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'publisher', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'description', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'caseSensitive', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'hierarchyMeaning', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'content', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'concept', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'concept.code', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'concept.display', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'concept.definition', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
#[FHIRProfileMustSupport(path: 'concept.concept', groups: ['http://hl7.org/fhir/StructureDefinition/shareablecodesystem'])]
class ShareableCodeSystemProfile extends CodeSystemResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareablecodesystem';
}
