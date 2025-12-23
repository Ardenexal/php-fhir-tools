<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Observation.referenceRange
 * @description Guidance on how to interpret the value by comparison to a normal or recommended range.  Multiple reference ranges are interpreted as an "OR".   In other words, to represent two distinct target populations, two `referenceRange` elements would be used.
 */
class FHIRObservationReferenceRange extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity low Low Range, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $low = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity high High Range, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $high = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept normalValue Normal value, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $normalValue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Reference range qualifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> appliesTo Reference range population */
		public array $appliesTo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange age Applicable age range, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange $age = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown text Text based reference range in an observation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $text = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
