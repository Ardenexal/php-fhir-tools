<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/fhirpathlibrary
 *
 * @description Represents a computable/executable FHIRPath logic library
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/fhirpathlibrary', baseType: 'Library', fhirVersion: 'R5')]
class FHIRPathLibraryProfile extends LogicLibraryProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/fhirpathlibrary';
}
