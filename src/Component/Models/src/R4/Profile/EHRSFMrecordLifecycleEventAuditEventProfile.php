<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEventResource;

/**
 * @author Health Level Seven International (Electronic Health Record)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent
 *
 * @description Defines the elements to be supported within the AuditEvent resource in order to conform with the Electronic Health Record System Functional Model Record Lifecycle Event standard
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent', baseType: 'AuditEvent', fhirVersion: 'R4')]
class EHRSFMrecordLifecycleEventAuditEventProfile extends AuditEventResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/ehrsrle-auditevent';
}
