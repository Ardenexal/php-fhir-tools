<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap;

/**
 * @description Content to create because of this mapping rule.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.target', fhirVersion: 'R4')]
class StructureMapGroupRuleTarget extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive context Type or variable this rule applies to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $context = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureMapContextTypeType contextType type | variable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureMapContextTypeType $contextType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string element Field to create in the context */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $element = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive variable Named context for field, if desired, and a field is specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $variable = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureMapTargetListModeType> listMode first | share | last | collate */
		public array $listMode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive listRuleId Internal rule reference for shared list items */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $listRuleId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureMapTransformType transform create | copy + */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureMapTransformType $transform = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap\StructureMapGroupRuleTargetParameter> parameter Parameters to the transform */
		public array $parameter = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
