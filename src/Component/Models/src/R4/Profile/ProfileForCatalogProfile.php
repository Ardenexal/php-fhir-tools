<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CompositionResource;

/**
 * @author Health Level Seven, Inc. - Clinical Quality Information WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/catalog
 *
 * @description A set of resources composed into a single coherent clinical statement with clinical attestation
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/catalog', baseType: 'Composition', fhirVersion: 'R4')]
class ProfileForCatalogProfile extends CompositionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/catalog';
}
