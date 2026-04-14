<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\EvidenceVariableResource;

/**
 * @author Health Level Seven, Inc. - Clinical Decision Support Workgroup
 *
 * @see http://hl7.org/fhir/StructureDefinition/picoelement
 *
 * @description Explanation of what this profile contains/is for.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/picoelement', baseType: 'EvidenceVariable', fhirVersion: 'R4')]
class PICOelementProfile extends EvidenceVariableResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/picoelement';
}
