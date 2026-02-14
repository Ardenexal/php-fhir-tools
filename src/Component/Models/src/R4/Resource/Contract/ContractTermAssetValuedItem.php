<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;

/**
 * @description Contract Valued Item List.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.asset.valuedItem', fhirVersion: 'R4')]
class ContractTermAssetValuedItem extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|Reference|null entityX Contract Valued Item Type */
        public CodeableConcept|Reference|null $entityX = null,
        /** @var Identifier|null identifier Contract Valued Item Number */
        public ?Identifier $identifier = null,
        /** @var DateTimePrimitive|null effectiveTime Contract Valued Item Effective Tiem */
        public ?DateTimePrimitive $effectiveTime = null,
        /** @var Quantity|null quantity Count of Contract Valued Items */
        public ?Quantity $quantity = null,
        /** @var Money|null unitPrice Contract Valued Item fee, charge, or cost */
        public ?Money $unitPrice = null,
        /** @var float|null factor Contract Valued Item Price Scaling Factor */
        public ?float $factor = null,
        /** @var float|null points Contract Valued Item Difficulty Scaling Factor */
        public ?float $points = null,
        /** @var Money|null net Total Contract Valued Item Value */
        public ?Money $net = null,
        /** @var StringPrimitive|string|null payment Terms of valuation */
        public StringPrimitive|string|null $payment = null,
        /** @var DateTimePrimitive|null paymentDate When payment is due */
        public ?DateTimePrimitive $paymentDate = null,
        /** @var Reference|null responsible Who will make payment */
        public ?Reference $responsible = null,
        /** @var Reference|null recipient Who will receive payment */
        public ?Reference $recipient = null,
        /** @var array<StringPrimitive|string> linkId Pointer to specific item */
        public array $linkId = [],
        /** @var array<UnsignedIntPrimitive> securityLabelNumber Security Labels that define affected terms */
        public array $securityLabelNumber = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
