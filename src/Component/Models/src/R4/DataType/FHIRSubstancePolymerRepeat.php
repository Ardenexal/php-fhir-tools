<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element SubstancePolymer.repeat
 * @description Todo.
 */
class FHIRSubstancePolymerRepeat extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger numberOfUnits Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $numberOfUnits = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string averageMolecularFormula Todo */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $averageMolecularFormula = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept repeatUnitAmountType Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $repeatUnitAmountType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstancePolymerRepeatRepeatUnit> repeatUnit Todo */
		public array $repeatUnit = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
