<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element InsurancePlan.plan.specificCost
 * @description Costs associated with the coverage provided by the product.
 */
class FHIRInsurancePlanPlanSpecificCost extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept category General category of benefit */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $category = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInsurancePlanPlanSpecificCostBenefit> benefit Benefits list */
		public array $benefit = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
