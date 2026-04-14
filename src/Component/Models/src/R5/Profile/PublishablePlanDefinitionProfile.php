<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/publishableplandefinition
 *
 * @description Supports declaration of the PlanDefinition metadata required by HL7 and other organizations that share and publish plandefinitions with a focus on the aspects of that metadata that are important for post-publication activities including distribution, inclusion in repositories, consumption, and implementation.
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/publishableplandefinition',
    baseType: 'PlanDefinition',
    fhirVersion: 'R5',
)]
class PublishablePlanDefinitionProfile extends ShareablePlanDefinitionProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/publishableplandefinition';
}
