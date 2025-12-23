<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element PlanDefinition.action.dynamicValue
 * @description Customizations that should be applied to the statically defined resource. For example, if the dosage of a medication must be computed based on the patient's weight, a customization would be used to specify an expression that calculated the weight, and the path on the resource that would contain the result.
 */
class FHIRPlanDefinitionActionDynamicValue extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string path The path to the element to be set dynamically */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExpression expression An expression that provides the dynamic value for the customization */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExpression $expression = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
