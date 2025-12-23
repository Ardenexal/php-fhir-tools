<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element StructureMap.group.rule
 * @description Transform Rule from source to target.
 */
class FHIRStructureMapGroupRule extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId name Name of the rule for internal references */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStructureMapGroupRuleSource> source Source inputs to the mapping */
		public array $source = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStructureMapGroupRuleTarget> target Content to create because of this mapping rule */
		public array $target = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStructureMapGroupRule> rule Rules contained in this rule */
		public array $rule = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStructureMapGroupRuleDependent> dependent Which other rules to apply in the context of this rule */
		public array $dependent = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string documentation Documentation for this instance of data */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $documentation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
