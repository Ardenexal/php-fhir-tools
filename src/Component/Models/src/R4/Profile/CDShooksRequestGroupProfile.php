<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\RequestGroupResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup
 *
 * @description Defines a RequestGroup that can represent a CDS Hooks response
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup', baseType: 'RequestGroup', fhirVersion: 'R4')]
class CDShooksRequestGroupProfile extends RequestGroupResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/cdshooksrequestgroup';
}
