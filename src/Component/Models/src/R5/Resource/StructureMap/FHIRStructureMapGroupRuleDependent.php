<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Which other rules to apply in the context of this rule.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.dependent', fhirVersion: 'R5')]
class FHIRStructureMapGroupRuleDependent extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId name Name of a rule or group to apply */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStructureMapGroupRuleTargetParameter> parameter Parameter to pass to the rule or group */
		public array $parameter = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
