<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SpecimenDefinition;

/**
 * @description The specimen's container.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SpecimenDefinition', elementPath: 'SpecimenDefinition.typeTested.container', fhirVersion: 'R4')]
class SpecimenDefinitionTypeTestedContainer extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept material Container material */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $material = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Kind of container associated with the kind of specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept cap Color of container cap */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $cap = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Container description */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity capacity Container capacity */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $capacity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string minimumVolumeX Minimum volume */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $minimumVolumeX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SpecimenDefinition\SpecimenDefinitionTypeTestedContainerAdditive> additive Additive associated with container */
		public array $additive = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string preparation Specimen container preparation */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $preparation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
