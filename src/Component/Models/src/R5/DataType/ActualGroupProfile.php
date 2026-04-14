<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\GroupResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/actualgroup
 *
 * @description Enforces an enumerated group, rather than a definitional group
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/actualgroup', baseType: 'Group', fhirVersion: 'R5')]
class ActualGroupProfile extends GroupResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/actualgroup';
}
