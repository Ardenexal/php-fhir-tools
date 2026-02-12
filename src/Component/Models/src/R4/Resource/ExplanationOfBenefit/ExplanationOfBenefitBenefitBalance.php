<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

/**
 * @description Balance by Benefit Category.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.benefitBalance', fhirVersion: 'R4')]
class ExplanationOfBenefitBenefitBalance extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept category Benefit classification */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $category = null,
		/** @var null|bool excluded Excluded from the plan */
		public ?bool $excluded = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Short name for the benefit */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Description of the benefit or services covered */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept network In or out of network */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $network = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept unit Individual or family */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $unit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept term Annual or lifetime */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $term = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitBenefitBalanceFinancial> financial Benefit Summary */
		public array $financial = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
