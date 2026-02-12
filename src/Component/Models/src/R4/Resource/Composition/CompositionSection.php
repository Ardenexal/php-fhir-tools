<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition;

/**
 * @description The root of the sections that make up the composition.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Composition', elementPath: 'Composition.section', fhirVersion: 'R4')]
class CompositionSection extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string title Label for section (e.g. for ToC) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Classification of section (recommended) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> author Who and/or what authored the section */
		public array $author = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference focus Who/what the section is about, when it is not about the subject of composition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $focus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the section, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ListModeType mode working | snapshot | changes */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ListModeType $mode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept orderedBy Order of section entries */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $orderedBy = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> entry A reference to data that supports this section */
		public array $entry = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept emptyReason Why the section is empty */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $emptyReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition\CompositionSection> section Nested Section */
		public array $section = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
