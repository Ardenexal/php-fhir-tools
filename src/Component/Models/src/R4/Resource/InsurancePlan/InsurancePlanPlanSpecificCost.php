<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan;

/**
 * @description Costs associated with the coverage provided by the product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.plan.specificCost', fhirVersion: 'R4')]
class InsurancePlanPlanSpecificCost extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept category General category of benefit */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $category = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan\InsurancePlanPlanSpecificCostBenefit> benefit Benefits list */
		public array $benefit = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
