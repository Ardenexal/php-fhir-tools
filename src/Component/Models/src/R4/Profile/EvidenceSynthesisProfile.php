<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\EvidenceResource;

/**
 * @author Health Level Seven, Inc. - Clinical Decision Support Workgroup
 *
 * @see http://hl7.org/fhir/StructureDefinition/synthesis
 *
 * @description Explanation of what this profile contains/is for.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/synthesis', baseType: 'Evidence', fhirVersion: 'R4')]
class EvidenceSynthesisProfile extends EvidenceResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/synthesis';
}
