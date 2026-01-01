<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;

/**
 * @description Medications particularly relevant for this condition.
 */
#[FHIRBackboneElement(parentResource: 'ConditionDefinition', elementPath: 'ConditionDefinition.medication', fhirVersion: 'R5')]
class FHIRConditionDefinitionMedication extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null category Category that is relevant */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null code Code for relevant Medication */
        public ?FHIRCodeableConcept $code = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
