<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\RequestOrchestrationResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration
 *
 * @description Defines a RequestOrchestration that can represent a CDS Hooks response
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration',
    baseType: 'RequestOrchestration',
    fhirVersion: 'R5',
)]
#[FHIRProfileConstraint(
    path: 'identifier',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'],
)]
#[FHIRProfileConstraint(
    path: 'instantiatesUri',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'],
)]
#[FHIRProfileMustSupport(path: 'priority', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'subject', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'authoredOn', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'author', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'action.title', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'action.description', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'action.priority', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'action.documentation', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'action.condition', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'action.timing[x]', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'action.participant', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'action.type', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'action.selectionBehavior', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
#[FHIRProfileMustSupport(path: 'action.resource', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration'])]
class CDSHooksRequestOrchestrationProfile extends RequestOrchestrationResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration';
}
