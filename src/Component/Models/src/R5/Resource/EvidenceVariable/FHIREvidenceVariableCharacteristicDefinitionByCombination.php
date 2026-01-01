<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCharacteristicCombinationType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Defines the characteristic as a combination of two or more characteristics.
 */
#[FHIRBackboneElement(
    parentResource: 'EvidenceVariable',
    elementPath: 'EvidenceVariable.characteristic.definitionByCombination',
    fhirVersion: 'R5',
)]
class FHIREvidenceVariableCharacteristicDefinitionByCombination extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCharacteristicCombinationType|null code all-of | any-of | at-least | at-most | statistical | net-effect | dataset */
        #[NotBlank]
        public ?FHIRCharacteristicCombinationType $code = null,
        /** @var FHIRPositiveInt|null threshold Provides the value of "n" when "at-least" or "at-most" codes are used */
        public ?FHIRPositiveInt $threshold = null,
        /** @var array<FHIREvidenceVariableCharacteristic> characteristic A defining factor of the characteristic */
        public array $characteristic = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
