<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
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
class CDSHooksRequestOrchestrationProfile extends RequestOrchestrationResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/cdshooksrequestorchestration';
}
