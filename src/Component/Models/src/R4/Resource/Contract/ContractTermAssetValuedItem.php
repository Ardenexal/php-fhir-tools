<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

/**
 * @description Contract Valued Item List.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.asset.valuedItem', fhirVersion: 'R4')]
class ContractTermAssetValuedItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference entityX Contract Valued Item Type */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $entityX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier identifier Contract Valued Item Number */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive effectiveTime Contract Valued Item Effective Tiem */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $effectiveTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity quantity Count of Contract Valued Items */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money unitPrice Contract Valued Item fee, charge, or cost */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $unitPrice = null,
		/** @var null|float factor Contract Valued Item Price Scaling Factor */
		public ?float $factor = null,
		/** @var null|float points Contract Valued Item Difficulty Scaling Factor */
		public ?float $points = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money net Total Contract Valued Item Value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $net = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string payment Terms of valuation */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $payment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive paymentDate When payment is due */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $paymentDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference responsible Who will make payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $responsible = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference recipient Who will receive payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $recipient = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> linkId Pointer to specific item */
		public array $linkId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive> securityLabelNumber Security Labels that define affected terms */
		public array $securityLabelNumber = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
