<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Content to create because of this mapping rule.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.target', fhirVersion: 'R4')]
class FHIRStructureMapGroupRuleTarget extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId context Type or variable this rule applies to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId $context = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRStructureMapContextTypeType contextType type | variable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRStructureMapContextTypeType $contextType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string element Field to create in the context */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $element = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId variable Named context for field, if desired, and a field is specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId $variable = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRStructureMapTargetListModeType> listMode first | share | last | collate */
		public array $listMode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId listRuleId Internal rule reference for shared list items */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId $listRuleId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRStructureMapTransformType transform create | copy + */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRStructureMapTransformType $transform = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStructureMapGroupRuleTargetParameter> parameter Parameters to the transform */
		public array $parameter = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
