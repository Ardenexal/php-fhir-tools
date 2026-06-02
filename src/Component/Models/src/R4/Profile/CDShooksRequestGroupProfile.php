<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\RequestGroupResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup
 *
 * @description Defines a RequestGroup that can represent a CDS Hooks response
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup', baseType: 'RequestGroup', fhirVersion: 'R4')]
#[FHIRProfileConstraint(
    path: 'identifier',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'],
)]
#[FHIRProfileConstraint(
    path: 'instantiatesUri',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'],
)]
#[FHIRProfileMustSupport(path: 'priority', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'subject', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'authoredOn', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'author', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'action.title', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'action.description', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'action.priority', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'action.documentation', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'action.condition', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'action.timing[x]', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'action.participant', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'action.type', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'action.selectionBehavior', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
#[FHIRProfileMustSupport(path: 'action.resource', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup'])]
class CDShooksRequestGroupProfile extends RequestGroupResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup';
}
