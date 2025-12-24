<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description A grouping (or set of values) described along with other groupings to specify the set of groupings allowed for the variable.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EvidenceVariable', elementPath: 'EvidenceVariable.category', fhirVersion: 'R4B')]
class FHIREvidenceVariableCategory extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Description of the grouping */
        public FHIRString|string|null $name = null,
        /** @var FHIRCodeableConcept|FHIRQuantity|FHIRRange|null valueX Definition of the grouping */
        public FHIRCodeableConcept|FHIRQuantity|FHIRRange|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
