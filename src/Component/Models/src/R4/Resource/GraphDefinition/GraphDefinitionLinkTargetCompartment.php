<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\GraphDefinition;

/**
 * @description Compartment Consistency Rules.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'GraphDefinition', elementPath: 'GraphDefinition.link.target.compartment', fhirVersion: 'R4')]
class GraphDefinitionLinkTargetCompartment extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\GraphCompartmentUseType use condition | requirement */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\GraphCompartmentUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CompartmentTypeType code Patient | Encounter | RelatedPerson | Practitioner | Device */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CompartmentTypeType $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\GraphCompartmentRuleType rule identical | matching | different | custom */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\GraphCompartmentRuleType $rule = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string expression Custom rule, as a FHIRPath expression */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $expression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Documentation for FHIRPath expression */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
