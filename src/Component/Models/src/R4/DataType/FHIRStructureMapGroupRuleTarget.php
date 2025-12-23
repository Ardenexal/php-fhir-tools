<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element StructureMap.group.rule.target
 * @description Content to create because of this mapping rule.
 */
class FHIRStructureMapGroupRuleTarget extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId context Type or variable this rule applies to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId $context = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStructureMapContextTypeType contextType type | variable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStructureMapContextTypeType $contextType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string element Field to create in the context */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $element = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId variable Named context for field, if desired, and a field is specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId $variable = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStructureMapTargetListModeType> listMode first | share | last | collate */
		public array $listMode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId listRuleId Internal rule reference for shared list items */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId $listRuleId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStructureMapTransformType transform create | copy + */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStructureMapTransformType $transform = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStructureMapGroupRuleTargetParameter> parameter Parameters to the transform */
		public array $parameter = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
