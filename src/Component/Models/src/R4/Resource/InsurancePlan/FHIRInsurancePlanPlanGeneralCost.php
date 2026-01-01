<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Overall costs associated with the plan.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.plan.generalCost', fhirVersion: 'R4')]
class FHIRInsurancePlanPlanGeneralCost extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type Type of cost */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt groupSize Number of enrollees */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt $groupSize = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMoney cost Cost value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMoney $cost = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string comment Additional cost information */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $comment = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
