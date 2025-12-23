<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element EvidenceVariable.characteristic.timeFromStart
 * @description Indicates duration, period, or point of observation from the participant's study entry.
 */
class FHIREvidenceVariableCharacteristicTimeFromStart extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string description Human readable description */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity quantity Used to express the observation at a defined amount of time after the study start */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange range Used to express the observation within a period after the study start */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange $range = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAnnotation> note Used for footnotes or explanatory notes */
		public array $note = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
