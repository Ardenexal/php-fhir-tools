<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Evidence.statistic.modelCharacteristic.variable
 * @description A variable adjusted for in the adjusted analysis.
 */
class FHIREvidenceStatisticModelCharacteristicVariable extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference variableDefinition Description of the variable */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $variableDefinition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREvidenceVariableHandlingType handling continuous | dichotomous | ordinal | polychotomous */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREvidenceVariableHandlingType $handling = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> valueCategory Description for grouping of ordinal or polychotomous variables */
		public array $valueCategory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity> valueQuantity Discrete value for grouping of ordinal or polychotomous variables */
		public array $valueQuantity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange> valueRange Range of values for grouping of ordinal or polychotomous variables */
		public array $valueRange = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
