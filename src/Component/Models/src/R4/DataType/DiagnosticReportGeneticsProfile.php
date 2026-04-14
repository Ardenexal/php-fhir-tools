<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DiagnosticReportResource;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/diagnosticreport-genetics
 *
 * @description Describes how the DiagnosticReport resource is used to report structured genetic test results
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/diagnosticreport-genetics',
    baseType: 'DiagnosticReport',
    fhirVersion: 'R4',
)]
class DiagnosticReportGeneticsProfile extends DiagnosticReportResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/diagnosticreport-genetics';
}
