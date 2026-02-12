<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

/**
 * @description If this item is a group then the values here are a summary of the adjudication of the detail items. If this item is a simple product or service then this is the result of the adjudication of this item.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.item.adjudication', fhirVersion: 'R4')]
class ExplanationOfBenefitItemAdjudication extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept category Type of adjudication information */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept reason Explanation of adjudication outcome */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $reason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money amount Monetary amount */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $amount = null,
		/** @var null|float value Non-monitary value */
		public ?float $value = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
