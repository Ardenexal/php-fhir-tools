<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element SubstancePolymer.repeat.repeatUnit.degreeOfPolymerisation
 * @description Applies to homopolymer and block co-polymers where the degree of polymerisation within a block can be described.
 */
class FHIRSubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type The type of the degree of polymerisation shall be described, e.g. SRU/Polymer Ratio */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger average An average amount of polymerisation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $average = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger low A low expected limit of the amount */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $low = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger high A high expected limit of the amount */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $high = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
