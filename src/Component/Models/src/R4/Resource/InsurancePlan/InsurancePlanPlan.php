<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan;

/**
 * @description Details about an insurance plan.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.plan', fhirVersion: 'R4')]
class InsurancePlanPlan extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifier for Product */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Type of plan */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> coverageArea Where product applies */
		public array $coverageArea = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> network What networks provide coverage */
		public array $network = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan\InsurancePlanPlanGeneralCost> generalCost Overall costs */
		public array $generalCost = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan\InsurancePlanPlanSpecificCost> specificCost Specific costs */
		public array $specificCost = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
