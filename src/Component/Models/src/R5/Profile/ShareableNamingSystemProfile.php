<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\NamingSystemResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/shareablenamingsystem
 *
 * @description Enforces the minimum information set for the naming system metadata required by HL7 and other organizations that share and publish naming systems
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/shareablenamingsystem', baseType: 'NamingSystem', fhirVersion: 'R5')]
class ShareableNamingSystemProfile extends NamingSystemResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareablenamingsystem';
}
