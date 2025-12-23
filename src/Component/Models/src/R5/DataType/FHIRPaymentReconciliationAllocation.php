<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element PaymentReconciliation.allocation
 * @description Distribution of the payment amount for a previously acknowledged payable.
 */
class FHIRPaymentReconciliationAllocation extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier identifier Business identifier of the payment detail */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier predecessor Business identifier of the prior payment detail */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier $predecessor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference target Subject of the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $target = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt targetItemX Sub-element of the subject */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt|null $targetItemX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference encounter Applied-to encounter */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference account Applied-to account */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $account = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Category of payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference submitter Submitter of the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $submitter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference response Response committing to a payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $response = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate date Date of commitment to pay */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference responsible Contact for the response */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $responsible = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference payee Recipient of the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $payee = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney amount Amount allocated to this payable */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
