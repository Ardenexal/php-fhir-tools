<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\NamingSystemResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/shareablenamingsystem
 *
 * @description Enforces the minimum information set for the naming system metadata required by HL7 and other organizations that share and publish naming systems
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/shareablenamingsystem', baseType: 'NamingSystem', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'url',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'],
)]
#[FHIRProfileConstraint(
    path: 'version',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'],
)]
#[FHIRProfileConstraint(
    path: 'title',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'],
)]
#[FHIRProfileConstraint(
    path: 'experimental',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'],
)]
#[FHIRProfileConstraint(
    path: 'description',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'],
)]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'])]
#[FHIRProfileMustSupport(path: 'url', groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'])]
#[FHIRProfileMustSupport(path: 'version', groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'])]
#[FHIRProfileMustSupport(path: 'versionAlgorithm[x]', groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'])]
#[FHIRProfileMustSupport(path: 'name', groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'])]
#[FHIRProfileMustSupport(path: 'title', groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'])]
#[FHIRProfileMustSupport(path: 'status', groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'])]
#[FHIRProfileMustSupport(path: 'experimental', groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'])]
#[FHIRProfileMustSupport(path: 'publisher', groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'])]
#[FHIRProfileMustSupport(path: 'description', groups: ['http://hl7.org/fhir/StructureDefinition/shareablenamingsystem'])]
class ShareableNamingSystemProfile extends NamingSystemResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareablenamingsystem';
}
