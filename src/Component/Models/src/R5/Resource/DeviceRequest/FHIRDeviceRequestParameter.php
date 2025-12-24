<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;

/**
 * @description Specific parameters for the ordered item.  For example, the prism value for lenses.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DeviceRequest', elementPath: 'DeviceRequest.parameter', fhirVersion: 'R5')]
class FHIRDeviceRequestParameter extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Device detail */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRCodeableConcept|FHIRQuantity|FHIRRange|FHIRBoolean|null valueX Value of detail */
        public FHIRCodeableConcept|FHIRQuantity|FHIRRange|FHIRBoolean|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
