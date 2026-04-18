<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ActivityDefinitionResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition
 *
 * @description Enforces the minimum information set for the activity definition metadata required by HL7 and other organizations that share and publish activity definitions
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition',
    baseType: 'ActivityDefinition',
    fhirVersion: 'R5',
)]
class ShareableActivityDefinitionProfile extends ActivityDefinitionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition';
}
