<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/modelinfolibrary
 *
 * @description Represents a computable representation of a model information library
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/modelinfolibrary', baseType: 'Library', fhirVersion: 'R5')]
class ModelInfoLibraryProfile extends ShareableLibraryProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/modelinfolibrary';
}
