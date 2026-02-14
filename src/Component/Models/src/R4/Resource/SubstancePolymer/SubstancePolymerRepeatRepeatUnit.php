<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer;

/**
 * @description Todo.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.repeat.repeatUnit', fhirVersion: 'R4')]
class SubstancePolymerRepeatRepeatUnit extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept orientationOfPolymerisation Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $orientationOfPolymerisation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string repeatUnit Todo */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $repeatUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\SubstanceAmount amount Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\SubstanceAmount $amount = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer\SubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation> degreeOfPolymerisation Todo */
		public array $degreeOfPolymerisation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer\SubstancePolymerRepeatRepeatUnitStructuralRepresentation> structuralRepresentation Todo */
		public array $structuralRepresentation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
