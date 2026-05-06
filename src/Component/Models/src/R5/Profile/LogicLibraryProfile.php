<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;

/**
 * @author Health Level Seven, Inc. - CDS WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/logiclibrary
 *
 * @description The logic library profile sets the minimum expectations for computable and/or executable libraries, including support for terminology and dependency declaration, parameters, and data requirements
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/logiclibrary', baseType: 'Library', fhirVersion: 'R5')]
class LogicLibraryProfile extends ShareableLibraryProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/logiclibrary';
}
