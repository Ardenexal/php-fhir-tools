<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/MoneyQuantity
 *
 * @description An amount of money. With regard to precision, see [Decimal Precision](datatypes.html#precision)
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/MoneyQuantity', baseType: 'Quantity', fhirVersion: 'R4B')]
#[FHIRProfileInvariant(
    path: '',
    key: 'mqty-1',
    severity: 'error',
    expression: '(code.exists() or value.empty()) and (system.empty() or system = \'urn:iso:std:iso:4217\')',
    human: 'There SHALL be a code if there is a value and it SHALL be an expression of currency.  If system is present, it SHALL be ISO 4217 (system = "urn:iso:std:iso:4217" - currency).',
    groups: ['http://hl7.org/fhir/StructureDefinition/MoneyQuantity'],
)]
class MoneyQuantityProfile extends Quantity
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/MoneyQuantity';
}
