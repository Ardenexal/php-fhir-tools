<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ProvenanceResource;

/**
 * @author Health Level Seven International (Electronic Health Record)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance
 *
 * @description Defines the elements to be supported within the Provenance resource in order to conform with the Electronic Health Record System Functional Model Record Lifecycle Event standard
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance', baseType: 'Provenance', fhirVersion: 'R4')]
#[FHIRProfileMustSupport(path: 'target', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance'])]
#[FHIRProfileMustSupport(path: 'occurred[x]', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance'])]
#[FHIRProfileMustSupport(path: 'recorded', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance'])]
#[FHIRProfileMustSupport(path: 'policy', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance'])]
#[FHIRProfileMustSupport(path: 'location', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance'])]
#[FHIRProfileMustSupport(path: 'reason', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance'])]
#[FHIRProfileMustSupport(path: 'activity', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance'])]
#[FHIRProfileMustSupport(path: 'agent', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance'])]
#[FHIRProfileMustSupport(path: 'agent.who', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance'])]
#[FHIRProfileMustSupport(path: 'agent.onBehalfOf', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance'])]
#[FHIRProfileMustSupport(path: 'signature', groups: ['http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance'])]
class EHRSFMrecordLifecycleEventProvenanceProfile extends ProvenanceResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/ehrsrle-provenance';
}
