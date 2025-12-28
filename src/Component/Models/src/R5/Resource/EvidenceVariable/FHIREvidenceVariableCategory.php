<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description A grouping for ordinal or polychotomous variables.
 */
#[FHIRBackboneElement(parentResource: 'EvidenceVariable', elementPath: 'EvidenceVariable.category', fhirVersion: 'R5')]
class FHIREvidenceVariableCategory extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Description of the grouping */
        public \FHIRString|string|null $name = null,
        /** @var FHIRCodeableConcept|FHIRQuantity|FHIRRange|null valueX Definition of the grouping */
        public \FHIRCodeableConcept|\FHIRQuantity|\FHIRRange|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
