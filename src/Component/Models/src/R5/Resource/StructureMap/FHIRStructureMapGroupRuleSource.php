<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Source inputs to the mapping.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.source', fhirVersion: 'R5')]
class FHIRStructureMapGroupRuleSource extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId context Type or variable this rule applies to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $context = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger min Specified minimum cardinality */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $min = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string max Specified maximum cardinality (number or *) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $max = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string type Rule only applies if source has this type */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string defaultValue Default value if no value exists */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $defaultValue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string element Optional field for this source */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $element = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRStructureMapSourceListModeType listMode first | not_first | last | not_last | only_one */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRStructureMapSourceListModeType $listMode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId variable Named context for field, if a field is specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $variable = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string condition FHIRPath expression  - must be true or the rule does not apply */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $condition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string check FHIRPath expression  - must be true or the mapping engine throws an error instead of completing */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $check = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string logMessage Message to put in log if source exists (FHIRPath) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $logMessage = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
