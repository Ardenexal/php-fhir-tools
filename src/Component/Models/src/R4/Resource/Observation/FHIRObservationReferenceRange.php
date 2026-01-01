<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Guidance on how to interpret the value by comparison to a normal or recommended range.  Multiple reference ranges are interpreted as an "OR".   In other words, to represent two distinct target populations, two `referenceRange` elements would be used.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Observation', elementPath: 'Observation.referenceRange', fhirVersion: 'R4')]
class FHIRObservationReferenceRange extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity low Low Range, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity $low = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity high High Range, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity $high = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type Reference range qualifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> appliesTo Reference range population */
		public array $appliesTo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange age Applicable age range, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange $age = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string text Text based reference range in an observation */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $text = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
