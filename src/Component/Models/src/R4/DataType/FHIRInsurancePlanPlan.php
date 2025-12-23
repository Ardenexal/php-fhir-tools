<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element InsurancePlan.plan
 * @description Details about an insurance plan.
 */
class FHIRInsurancePlanPlan extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier Business Identifier for Product */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type Type of plan */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> coverageArea Where product applies */
		public array $coverageArea = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> network What networks provide coverage */
		public array $network = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInsurancePlanPlanGeneralCost> generalCost Overall costs */
		public array $generalCost = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInsurancePlanPlanSpecificCost> specificCost Specific costs */
		public array $specificCost = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
