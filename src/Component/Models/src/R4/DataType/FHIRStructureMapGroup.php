<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element StructureMap.group
 * @description Organizes the mapping into manageable chunks for human review/ease of maintenance.
 */
class FHIRStructureMapGroup extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId name Human-readable label */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId extends Another group that this group adds rules to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId $extends = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStructureMapGroupTypeModeType typeMode none | types | type-and-types */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStructureMapGroupTypeModeType $typeMode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string documentation Additional description/explanation for group */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $documentation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStructureMapGroupInput> input Named instance provided when invoking the map */
		public array $input = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStructureMapGroupRule> rule Transform Rule from source to target */
		public array $rule = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
