<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\GroupResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/groupdefinition
 *
 * @description Enforces a descriptive group that can be used in definitional resources
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/groupdefinition', baseType: 'Group', fhirVersion: 'R4B')]
class GroupDefinitionProfile extends GroupResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/groupdefinition';
}
