<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Distribution of the payment amount for a previously acknowledged payable.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PaymentReconciliation', elementPath: 'PaymentReconciliation.allocation', fhirVersion: 'R5')]
class FHIRPaymentReconciliationAllocation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier identifier Business identifier of the payment detail */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier predecessor Business identifier of the prior payment detail */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier $predecessor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference target Subject of the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $target = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt targetItemX Sub-element of the subject */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt|null $targetItemX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference encounter Applied-to encounter */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference account Applied-to account */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $account = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type Category of payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference submitter Submitter of the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $submitter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference response Response committing to a payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $response = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate date Date of commitment to pay */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference responsible Contact for the response */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $responsible = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference payee Recipient of the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $payee = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney amount Amount allocated to this payable */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
