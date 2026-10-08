<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\DiagnosticReportResource;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/hlaresult
 *
 * @description Describes how the HLA genotyping results
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/hlaresult', baseType: 'DiagnosticReport', fhirVersion: 'R4B')]
class ProfileForHLAGenotypingResultsProfile extends DiagnosticReportResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/hlaresult';
}
