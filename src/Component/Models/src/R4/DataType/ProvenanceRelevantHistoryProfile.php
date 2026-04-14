<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ProvenanceResource;

/**
 * @author Health Level Seven International
 *
 * @see http://hl7.org/fhir/StructureDefinition/provenance-relevant-history
 *
 * @description Guidance on using Provenance for related history elements
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/provenance-relevant-history',
    baseType: 'Provenance',
    fhirVersion: 'R4',
)]
class ProvenanceRelevantHistoryProfile extends ProvenanceResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/provenance-relevant-history';
}
