<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CatalogEntry;

/**
 * @description Used for example, to point to a substance, or to a device used to administer a medication.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CatalogEntry', elementPath: 'CatalogEntry.relatedEntry', fhirVersion: 'R4')]
class CatalogEntryRelatedEntry extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CatalogEntryRelationTypeType relationtype triggers | is-replaced-by */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CatalogEntryRelationTypeType $relationtype = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference item The reference to the related item */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $item = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
