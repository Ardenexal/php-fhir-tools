<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEventResource;

/**
 * @author Health Level Seven International (Electronic Health Record)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent
 *
 * @description Defines the elements to be supported within the AuditEvent resource in order to conform with the Electronic Health Record System Functional Model Record Lifecycle Event standard
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent', baseType: 'AuditEvent', fhirVersion: 'R4')]
#[FHIRProfileMustSupport(path: 'type', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'subtype', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'action', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'recorded', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'purposeOfEvent', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'agent', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'agent.role', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'agent.who', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'agent.requestor', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'agent.location', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'agent.policy', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'agent.network', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'agent.network.address', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'agent.network.type', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'agent.purposeOfUse', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'source', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'source.site', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'source.observer', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'source.type', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'entity', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'entity.what', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'entity.type', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'entity.role', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'entity.lifecycle', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
#[FHIRProfileMustSupport(path: 'entity.securityLabel', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent'])]
class EHRSFMrecordLifecycleEventAuditEventProfile extends AuditEventResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent';
}
