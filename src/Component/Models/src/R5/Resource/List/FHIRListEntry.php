<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Entries in this list.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'List', elementPath: 'List.entry', fhirVersion: 'R5')]
class FHIRListEntry extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept flag Status/Workflow information about this item */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $flag = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean deleted If this item is actually marked as deleted */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $deleted = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime date When item added to list */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference item Actual entry */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $item = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
