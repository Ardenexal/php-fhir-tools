<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRConditionPreconditionTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An observation that suggests that this condition applies.
 */
#[FHIRBackboneElement(parentResource: 'ConditionDefinition', elementPath: 'ConditionDefinition.precondition', fhirVersion: 'R5')]
class FHIRConditionDefinitionPrecondition extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRConditionPreconditionTypeType|null type sensitive | specific */
        #[NotBlank]
        public ?FHIRConditionPreconditionTypeType $type = null,
        /** @var FHIRCodeableConcept|null code Code for relevant Observation */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRCodeableConcept|FHIRQuantity|null valueX Value of Observation */
        public FHIRCodeableConcept|FHIRQuantity|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
