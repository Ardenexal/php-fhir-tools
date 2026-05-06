<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ServiceRequestResource;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/servicerequest-genetics
 *
 * @description Describes how the ServiceRequest resource is used to for genetics
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/servicerequest-genetics',
    baseType: 'ServiceRequest',
    fhirVersion: 'R4',
)]
class ServiceRequestGeneticsProfile extends ServiceRequestResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/servicerequest-genetics';
}
