<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Defines the characteristic using both a type and value[x] elements.
 */
#[FHIRBackboneElement(
    parentResource: 'EvidenceVariable',
    elementPath: 'EvidenceVariable.characteristic.definitionByTypeAndValue',
    fhirVersion: 'R5',
)]
class FHIREvidenceVariableCharacteristicDefinitionByTypeAndValue extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Expresses the type of characteristic */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> method Method for how the characteristic value was determined */
        public array $method = [],
        /** @var FHIRReference|null device Device used for determining characteristic */
        public ?FHIRReference $device = null,
        /** @var FHIRCodeableConcept|FHIRBoolean|FHIRQuantity|FHIRRange|FHIRReference|FHIRId|null valueX Defines the characteristic when coupled with characteristic.type */
        #[NotBlank]
        public FHIRCodeableConcept|FHIRBoolean|FHIRQuantity|FHIRRange|FHIRReference|FHIRId|null $valueX = null,
        /** @var FHIRCodeableConcept|null offset Reference point for valueQuantity or valueRange */
        public ?FHIRCodeableConcept $offset = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
