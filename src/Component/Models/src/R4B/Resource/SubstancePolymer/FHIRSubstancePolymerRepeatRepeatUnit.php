<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Todo.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.repeat.repeatUnit', fhirVersion: 'R4B')]
class FHIRSubstancePolymerRepeatRepeatUnit extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept orientationOfPolymerisation Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $orientationOfPolymerisation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string repeatUnit Todo */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $repeatUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSubstanceAmount amount Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSubstanceAmount $amount = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation> degreeOfPolymerisation Todo */
		public array $degreeOfPolymerisation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstancePolymerRepeatRepeatUnitStructuralRepresentation> structuralRepresentation Todo */
		public array $structuralRepresentation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
