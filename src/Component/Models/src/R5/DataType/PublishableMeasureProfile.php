<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/publishablemeasure
 *
 * @description Supports declaration of the Measure metadata required by HL7 and other organizations that share and publish measures with a focus on the aspects of that metadata that are important for post-publication activities including distribution, inclusion in repositories, consumption, and implementation.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/publishablemeasure', baseType: 'Measure', fhirVersion: 'R5')]
class PublishableMeasureProfile extends ShareableMeasureProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/publishablemeasure';
}
