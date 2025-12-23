<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Contract.term.asset.valuedItem
 * @description Contract Valued Item List.
 */
class FHIRContractTermAssetValuedItem extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference entityX Contract Valued Item Type */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|null $entityX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier identifier Contract Valued Item Number */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime effectiveTime Contract Valued Item Effective Tiem */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime $effectiveTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity quantity Count of Contract Valued Items */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMoney unitPrice Contract Valued Item fee, charge, or cost */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMoney $unitPrice = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal factor Contract Valued Item Price Scaling Factor */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal points Contract Valued Item Difficulty Scaling Factor */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $points = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMoney net Total Contract Valued Item Value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMoney $net = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string payment Terms of valuation */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $payment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime paymentDate When payment is due */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime $paymentDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference responsible Who will make payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $responsible = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference recipient Who will receive payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $recipient = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string> linkId Pointer to specific item */
		public array $linkId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnsignedInt> securityLabelNumber Security Labels that define affected terms */
		public array $securityLabelNumber = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
