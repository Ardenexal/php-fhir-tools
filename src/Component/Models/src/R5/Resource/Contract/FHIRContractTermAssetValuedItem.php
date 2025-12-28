<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Contract Valued Item List.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.asset.valuedItem', fhirVersion: 'R5')]
class FHIRContractTermAssetValuedItem extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference entityX Contract Valued Item Type */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|null $entityX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier identifier Contract Valued Item Number */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime effectiveTime Contract Valued Item Effective Tiem */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $effectiveTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity quantity Count of Contract Valued Items */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney unitPrice Contract Valued Item fee, charge, or cost */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney $unitPrice = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal factor Contract Valued Item Price Scaling Factor */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal points Contract Valued Item Difficulty Scaling Factor */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $points = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney net Total Contract Valued Item Value */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney $net = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string payment Terms of valuation */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $payment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime paymentDate When payment is due */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $paymentDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference responsible Who will make payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $responsible = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference recipient Who will receive payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $recipient = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> linkId Pointer to specific item */
		public array $linkId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt> securityLabelNumber Security Labels that define affected terms */
		public array $securityLabelNumber = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
