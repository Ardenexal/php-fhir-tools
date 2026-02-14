<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer;

/**
 * @description Todo.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'SubstancePolymer',
	elementPath: 'SubstancePolymer.repeat.repeatUnit.degreeOfPolymerisation',
	fhirVersion: 'R4',
)]
class SubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept degree Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $degree = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\SubstanceAmount amount Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\SubstanceAmount $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
