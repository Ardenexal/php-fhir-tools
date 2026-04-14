<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/executablevalueset
 *
 * @description Defines an executable value set as one that SHALL have an expansion included, as well as a usage warning indicating the expansion is a point-in-time snapshot and must be maintained over time for production usage. The value set expansion specifies the timestamp when the expansion was produced, SHOULD contain the parameters used for the expansion, and SHALL contain the codes that are obtained by evaluating the value set definition. If this is ONLY an executable value set, a computable definition of the value set must be obtained to compute the updated expansion.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/executablevalueset', baseType: 'ValueSet', fhirVersion: 'R5')]
class ExecutableValueSetProfile extends ShareableValueSetProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/executablevalueset';
}
