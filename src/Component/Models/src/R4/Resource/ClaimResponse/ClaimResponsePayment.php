<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse;

/**
 * @description Payment details for the adjudication of the claim.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.payment', fhirVersion: 'R4')]
class ClaimResponsePayment extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Partial or complete payment */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money adjustment Payment adjustment for non-claim issues */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $adjustment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept adjustmentReason Explanation for the adjustment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $adjustmentReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive date Expected date of payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money amount Payable amount after adjustment */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $amount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier identifier Business identifier for the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $identifier = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
