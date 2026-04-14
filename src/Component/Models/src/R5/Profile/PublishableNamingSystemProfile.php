<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/publishablenamingsystem
 *
 * @description Defines and enforces the minimum expectations for publication and distribution of a naming system, typically as part of an artifact repository or implementation guide publication
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/publishablenamingsystem',
    baseType: 'NamingSystem',
    fhirVersion: 'R5',
)]
class PublishableNamingSystemProfile extends ShareableNamingSystemProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/publishablenamingsystem';
}
