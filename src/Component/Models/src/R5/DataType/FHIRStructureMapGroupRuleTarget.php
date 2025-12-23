<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element StructureMap.group.rule.target
 * @description Content to create because of this mapping rule.
 */
class FHIRStructureMapGroupRuleTarget extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string context Variable this rule applies to */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $context = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string element Field to create in the context */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $element = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId variable Named context for field, if desired, and a field is specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $variable = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStructureMapTargetListModeType> listMode first | share | last | single */
		public array $listMode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId listRuleId Internal rule reference for shared list items */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $listRuleId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStructureMapTransformType transform create | copy + */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStructureMapTransformType $transform = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStructureMapGroupRuleTargetParameter> parameter Parameters to the transform */
		public array $parameter = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
