<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ExplanationOfBenefit.item.adjudication
 * @description If this item is a group then the values here are a summary of the adjudication of the detail items. If this item is a simple product or service then this is the result of the adjudication of this item.
 */
class FHIRExplanationOfBenefitItemAdjudication extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept category Type of adjudication information */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept reason Explanation of adjudication outcome */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $reason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMoney amount Monetary amount */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMoney $amount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal value Non-monitary value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $value = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
