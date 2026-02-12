<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer;

/**
 * @description Todo.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.repeat', fhirVersion: 'R4')]
class SubstancePolymerRepeat extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|int numberOfUnits Todo */
		public ?int $numberOfUnits = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string averageMolecularFormula Todo */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $averageMolecularFormula = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept repeatUnitAmountType Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $repeatUnitAmountType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer\SubstancePolymerRepeatRepeatUnit> repeatUnit Todo */
		public array $repeatUnit = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
