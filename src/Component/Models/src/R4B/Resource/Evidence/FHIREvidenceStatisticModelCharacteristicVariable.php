<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description A variable adjusted for in the adjusted analysis.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.statistic.modelCharacteristic.variable', fhirVersion: 'R4B')]
class FHIREvidenceStatisticModelCharacteristicVariable extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference variableDefinition Description of the variable */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $variableDefinition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIREvidenceVariableHandlingType handling continuous | dichotomous | ordinal | polychotomous */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIREvidenceVariableHandlingType $handling = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> valueCategory Description for grouping of ordinal or polychotomous variables */
		public array $valueCategory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity> valueQuantity Discrete value for grouping of ordinal or polychotomous variables */
		public array $valueQuantity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange> valueRange Range of values for grouping of ordinal or polychotomous variables */
		public array $valueRange = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
