<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap;

/**
 * @description Transform Rule from source to target.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule', fhirVersion: 'R4')]
class StructureMapGroupRule extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive name Name of the rule for internal references */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap\StructureMapGroupRuleSource> source Source inputs to the mapping */
		public array $source = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap\StructureMapGroupRuleTarget> target Content to create because of this mapping rule */
		public array $target = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap\StructureMapGroupRule> rule Rules contained in this rule */
		public array $rule = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap\StructureMapGroupRuleDependent> dependent Which other rules to apply in the context of this rule */
		public array $dependent = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string documentation Documentation for this instance of data */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $documentation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
