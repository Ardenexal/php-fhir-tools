<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Distribution of the payment amount for a previously acknowledged payable.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PaymentReconciliation', elementPath: 'PaymentReconciliation.detail', fhirVersion: 'R4')]
class FHIRPaymentReconciliationDetail extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier identifier Business identifier of the payment detail */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier predecessor Business identifier of the prior payment detail */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier $predecessor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type Category of payment */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference request Request giving rise to the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference submitter Submitter of the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $submitter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference response Response committing to a payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $response = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate date Date of commitment to pay */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference responsible Contact for the response */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $responsible = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference payee Recipient of the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $payee = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMoney amount Amount allocated to this payable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMoney $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
