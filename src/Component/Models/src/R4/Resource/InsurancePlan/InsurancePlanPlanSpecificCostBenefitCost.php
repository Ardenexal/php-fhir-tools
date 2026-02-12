<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan;

/**
 * @description List of the costs associated with a specific benefit.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.plan.specificCost.benefit.cost', fhirVersion: 'R4')]
class InsurancePlanPlanSpecificCostBenefitCost extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Type of cost */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept applicability in-network | out-of-network | other */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $applicability = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> qualifiers Additional information about the cost */
		public array $qualifiers = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity value The actual cost value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $value = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
