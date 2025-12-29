<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description An SRU - Structural Repeat Unit.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.repeat.repeatUnit', fhirVersion: 'R5')]
class FHIRSubstancePolymerRepeatRepeatUnit extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string unit Structural repeat units are essential elements for defining polymers */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $unit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept orientation The orientation of the polymerisation, e.g. head-tail, head-head, random */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $orientation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger amount Number of repeats of this unit */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $amount = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation> degreeOfPolymerisation Applies to homopolymer and block co-polymers where the degree of polymerisation within a block can be described */
		public array $degreeOfPolymerisation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstancePolymerRepeatRepeatUnitStructuralRepresentation> structuralRepresentation A graphical structure for this SRU */
		public array $structuralRepresentation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
