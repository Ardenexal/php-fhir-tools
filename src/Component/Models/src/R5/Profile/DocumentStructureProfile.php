<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\CompositionResource;

/**
 * @see http://hl7.org/fhir/StructureDefinition/example-composition
 *
 * @description Document Structure (For testing section templates)
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/example-composition', baseType: 'Composition', fhirVersion: 'R5')]
class DocumentStructureProfile extends CompositionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/example-composition';
}
