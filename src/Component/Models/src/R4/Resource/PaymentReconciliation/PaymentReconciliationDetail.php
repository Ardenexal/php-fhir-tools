<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\PaymentReconciliation;

/**
 * @description Distribution of the payment amount for a previously acknowledged payable.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PaymentReconciliation', elementPath: 'PaymentReconciliation.detail', fhirVersion: 'R4')]
class PaymentReconciliationDetail extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier identifier Business identifier of the payment detail */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier predecessor Business identifier of the prior payment detail */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $predecessor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Category of payment */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference request Request giving rise to the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference submitter Submitter of the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $submitter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference response Response committing to a payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $response = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive date Date of commitment to pay */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference responsible Contact for the response */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $responsible = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference payee Recipient of the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $payee = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money amount Amount allocated to this payable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
