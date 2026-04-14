<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/publishablelibrary
 *
 * @description Supports declaration of the library metadata required by HL7 and other organizations that share and publish libraries with a focus on the aspects of that metadata that are important for post-publication activities including distribution, inclusion in repositories, consumption, and implementation.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/publishablelibrary', baseType: 'Library', fhirVersion: 'R5')]
class PublishableLibraryProfile extends ShareableLibraryProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/publishablelibrary';
}
