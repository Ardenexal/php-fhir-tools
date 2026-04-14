<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\TestScriptResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/shareabletestscript
 *
 * @description Enforces the minimum information set for the test script metadata required by HL7 and other organizations that share and publish test scripts
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/shareabletestscript', baseType: 'TestScript', fhirVersion: 'R5')]
class ShareableTestScriptProfile extends TestScriptResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareabletestscript';
}
