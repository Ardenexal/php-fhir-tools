<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Applies to homopolymer and block co-polymers where the degree of polymerisation within a block can be described.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'SubstancePolymer',
	elementPath: 'SubstancePolymer.repeat.repeatUnit.degreeOfPolymerisation',
	fhirVersion: 'R5',
)]
class FHIRSubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type The type of the degree of polymerisation shall be described, e.g. SRU/Polymer Ratio */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger average An average amount of polymerisation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $average = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger low A low expected limit of the amount */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $low = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger high A high expected limit of the amount */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $high = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
