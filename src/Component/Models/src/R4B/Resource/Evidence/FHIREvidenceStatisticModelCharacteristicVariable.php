<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIREvidenceVariableHandlingType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A variable adjusted for in the adjusted analysis.
 */
#[FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.statistic.modelCharacteristic.variable', fhirVersion: 'R4B')]
class FHIREvidenceStatisticModelCharacteristicVariable extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null variableDefinition Description of the variable */
        #[NotBlank]
        public ?FHIRReference $variableDefinition = null,
        /** @var FHIREvidenceVariableHandlingType|null handling continuous | dichotomous | ordinal | polychotomous */
        public ?FHIREvidenceVariableHandlingType $handling = null,
        /** @var array<FHIRCodeableConcept> valueCategory Description for grouping of ordinal or polychotomous variables */
        public array $valueCategory = [],
        /** @var array<FHIRQuantity> valueQuantity Discrete value for grouping of ordinal or polychotomous variables */
        public array $valueQuantity = [],
        /** @var array<FHIRRange> valueRange Range of values for grouping of ordinal or polychotomous variables */
        public array $valueRange = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
