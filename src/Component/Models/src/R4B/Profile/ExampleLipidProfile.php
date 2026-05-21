<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\DiagnosticReportResource;

/**
 * @author Grahame Grieve
 *
 * @see http://hl7.org/fhir/StructureDefinition/lipidprofile
 *
 * @description Lipid Lab Report
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/lipidprofile', baseType: 'DiagnosticReport', fhirVersion: 'R4B')]
class ExampleLipidProfile extends DiagnosticReportResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/lipidprofile';
}
