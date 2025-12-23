<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element PaymentReconciliation.detail
 * @description Distribution of the payment amount for a previously acknowledged payable.
 */
class FHIRPaymentReconciliationDetail extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier identifier Business identifier of the payment detail */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier predecessor Business identifier of the prior payment detail */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier $predecessor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type Category of payment */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference request Request giving rise to the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference submitter Submitter of the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $submitter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference response Response committing to a payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $response = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate date Date of commitment to pay */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference responsible Contact for the response */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $responsible = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference payee Recipient of the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $payee = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMoney amount Amount allocated to this payable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMoney $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
