<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinitionResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/shareableplandefinition
 *
 * @description Enforces the minimum information set for the plan definition metadata required by HL7 and other organizations that share and publish plan definitions
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/shareableplandefinition',
    baseType: 'PlanDefinition',
    fhirVersion: 'R4',
)]
class ShareablePlanDefinitionProfile extends PlanDefinitionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareableplandefinition';
}
