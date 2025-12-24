<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;

/**
 * @description Specific parameters for the ordered item.  For example, the size of the indicated item.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SupplyRequest', elementPath: 'SupplyRequest.parameter', fhirVersion: 'R4B')]
class FHIRSupplyRequestParameter extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Item detail */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRCodeableConcept|FHIRQuantity|FHIRRange|FHIRBoolean|null valueX Value of detail */
        public FHIRCodeableConcept|FHIRQuantity|FHIRRange|FHIRBoolean|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
