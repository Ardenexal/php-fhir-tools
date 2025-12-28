<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/MonetaryComponent
 *
 * @description Availability data for an {item}.
 */
#[FHIRComplexType(typeName: 'MonetaryComponent', fhirVersion: 'R5')]
class FHIRMonetaryComponent extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRPriceComponentTypeType|null type base | surcharge | deduction | discount | tax | informational */
        #[NotBlank]
        public ?\FHIRPriceComponentTypeType $type = null,
        /** @var FHIRCodeableConcept|null code Codes may be used to differentiate between kinds of taxes, surcharges, discounts etc. */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRDecimal|null factor Factor used for calculating this component */
        public ?\FHIRDecimal $factor = null,
        /** @var FHIRMoney|null amount Explicit value amount to be used */
        public ?\FHIRMoney $amount = null,
    ) {
        parent::__construct($id, $extension);
    }
}
