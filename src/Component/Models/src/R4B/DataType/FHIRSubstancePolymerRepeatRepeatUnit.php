<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element SubstancePolymer.repeat.repeatUnit
 * @description Todo.
 */
class FHIRSubstancePolymerRepeatRepeatUnit extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept orientationOfPolymerisation Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $orientationOfPolymerisation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string repeatUnit Todo */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $repeatUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstanceAmount amount Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstanceAmount $amount = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation> degreeOfPolymerisation Todo */
		public array $degreeOfPolymerisation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstancePolymerRepeatRepeatUnitStructuralRepresentation> structuralRepresentation Todo */
		public array $structuralRepresentation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
