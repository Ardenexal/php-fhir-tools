<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element SpecimenDefinition.typeTested.container
 * @description The specimen's container.
 */
class FHIRSpecimenDefinitionTypeTestedContainer extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept material Container material */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $material = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type Kind of container associated with the kind of specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept cap Color of container cap */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $cap = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string description Container description */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity capacity Container capacity */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity $capacity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string minimumVolumeX Minimum volume */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $minimumVolumeX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSpecimenDefinitionTypeTestedContainerAdditive> additive Additive associated with container */
		public array $additive = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string preparation Specimen container preparation */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $preparation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
