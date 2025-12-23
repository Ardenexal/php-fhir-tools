<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ExplanationOfBenefit.payment
 * @description Payment details for the adjudication of the claim.
 */
class FHIRExplanationOfBenefitPayment extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Partial or complete payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney adjustment Payment adjustment for non-claim issues */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney $adjustment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept adjustmentReason Explanation for the variance */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $adjustmentReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate date Expected date of payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney amount Payable amount after adjustment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney $amount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier identifier Business identifier for the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier $identifier = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
