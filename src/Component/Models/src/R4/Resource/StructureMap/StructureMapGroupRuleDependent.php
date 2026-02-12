<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap;

/**
 * @description Which other rules to apply in the context of this rule.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.dependent', fhirVersion: 'R4')]
class StructureMapGroupRuleDependent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive name Name of a rule or group to apply */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> variable Variable to pass to the rule or group */
		public array $variable = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
