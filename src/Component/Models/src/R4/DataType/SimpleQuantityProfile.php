<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/SimpleQuantity
 *
 * @description A fixed quantity (no comparator)
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/SimpleQuantity', baseType: 'Quantity', fhirVersion: 'R4')]
class SimpleQuantityProfile extends Quantity
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/SimpleQuantity';
}
