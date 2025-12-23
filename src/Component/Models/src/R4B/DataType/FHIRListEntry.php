<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element List.entry
 * @description Entries in this list.
 */
class FHIRListEntry extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept flag Status/Workflow information about this item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $flag = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean deleted If this item is actually marked as deleted */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $deleted = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime date When item added to list */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference item Actual entry */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $item = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
