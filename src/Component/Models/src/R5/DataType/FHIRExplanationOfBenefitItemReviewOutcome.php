<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ExplanationOfBenefit.item.reviewOutcome
 * @description The high-level results of the adjudication if adjudication has been performed.
 */
class FHIRExplanationOfBenefitItemReviewOutcome extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept decision Result of the adjudication */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $decision = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> reason Reason for result of the adjudication */
		public array $reason = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string preAuthRef Preauthorization reference */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $preAuthRef = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod preAuthPeriod Preauthorization reference effective period */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod $preAuthPeriod = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
