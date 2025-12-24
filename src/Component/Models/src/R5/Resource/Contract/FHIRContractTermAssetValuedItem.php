<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt;

/**
 * @description Contract Valued Item List.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.asset.valuedItem', fhirVersion: 'R5')]
class FHIRContractTermAssetValuedItem extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|FHIRReference|null entityX Contract Valued Item Type */
        public FHIRCodeableConcept|FHIRReference|null $entityX = null,
        /** @var FHIRIdentifier|null identifier Contract Valued Item Number */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRDateTime|null effectiveTime Contract Valued Item Effective Tiem */
        public ?FHIRDateTime $effectiveTime = null,
        /** @var FHIRQuantity|null quantity Count of Contract Valued Items */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRMoney|null unitPrice Contract Valued Item fee, charge, or cost */
        public ?FHIRMoney $unitPrice = null,
        /** @var FHIRDecimal|null factor Contract Valued Item Price Scaling Factor */
        public ?FHIRDecimal $factor = null,
        /** @var FHIRDecimal|null points Contract Valued Item Difficulty Scaling Factor */
        public ?FHIRDecimal $points = null,
        /** @var FHIRMoney|null net Total Contract Valued Item Value */
        public ?FHIRMoney $net = null,
        /** @var FHIRString|string|null payment Terms of valuation */
        public FHIRString|string|null $payment = null,
        /** @var FHIRDateTime|null paymentDate When payment is due */
        public ?FHIRDateTime $paymentDate = null,
        /** @var FHIRReference|null responsible Who will make payment */
        public ?FHIRReference $responsible = null,
        /** @var FHIRReference|null recipient Who will receive payment */
        public ?FHIRReference $recipient = null,
        /** @var array<FHIRString|string> linkId Pointer to specific item */
        public array $linkId = [],
        /** @var array<FHIRUnsignedInt> securityLabelNumber Security Labels that define affected terms */
        public array $securityLabelNumber = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
