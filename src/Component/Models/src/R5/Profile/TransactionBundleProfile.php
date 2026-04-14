<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

/**
 * @see http://hl7.org/fhir/StructureDefinition/transaction-bundle
 *
 * @description This profile holds all the requirements and constraints related to a FHIR transaction.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/transaction-bundle', baseType: 'Bundle', fhirVersion: 'R5')]
class TransactionBundleProfile extends BundleResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/transaction-bundle';
}
