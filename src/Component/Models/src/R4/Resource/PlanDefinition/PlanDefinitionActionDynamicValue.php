<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinition;

/**
 * @description Customizations that should be applied to the statically defined resource. For example, if the dosage of a medication must be computed based on the patient's weight, a customization would be used to specify an expression that calculated the weight, and the path on the resource that would contain the result.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action.dynamicValue', fhirVersion: 'R4')]
class PlanDefinitionActionDynamicValue extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string path The path to the element to be set dynamically */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression expression An expression that provides the dynamic value for the customization */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression $expression = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
