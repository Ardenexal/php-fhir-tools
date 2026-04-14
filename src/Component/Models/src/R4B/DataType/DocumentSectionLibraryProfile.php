<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\CompositionResource;

/**
 * @see http://hl7.org/fhir/StructureDefinition/example-section-library
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/example-section-library',
    baseType: 'Composition',
    fhirVersion: 'R4B',
)]
class DocumentSectionLibraryProfile extends CompositionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/example-section-library';
}
