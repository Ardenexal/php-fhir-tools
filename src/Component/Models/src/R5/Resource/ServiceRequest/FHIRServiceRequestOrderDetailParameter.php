<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The parameter details for the service being requested.
 */
#[FHIRBackboneElement(parentResource: 'ServiceRequest', elementPath: 'ServiceRequest.orderDetail.parameter', fhirVersion: 'R5')]
class FHIRServiceRequestOrderDetailParameter extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code The detail of the order being requested */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRQuantity|FHIRRatio|FHIRRange|FHIRBoolean|FHIRCodeableConcept|FHIRString|string|FHIRPeriod|null valueX The value for the order detail */
        #[NotBlank]
        public FHIRQuantity|FHIRRatio|FHIRRange|FHIRBoolean|FHIRCodeableConcept|FHIRString|string|FHIRPeriod|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
