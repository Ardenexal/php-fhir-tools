<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\LibraryResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqllibrary
 *
 * @description Represents a CQL logic library
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/cqllibrary', baseType: 'Library', fhirVersion: 'R4')]
class CQLlibraryProfile extends LibraryResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/cqllibrary';
}
