<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/MoneyQuantity
 *
 * @description An amount of money. With regard to precision, see [Decimal Precision](datatypes.html#precision)
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/MoneyQuantity', baseType: 'Quantity', fhirVersion: 'R5')]
class MoneyQuantityProfile extends Quantity
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/MoneyQuantity';
}
