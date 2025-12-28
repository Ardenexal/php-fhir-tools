<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAddress;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The descriptive or identifying characteristics of the item.
 */
#[FHIRBackboneElement(parentResource: 'InventoryItem', elementPath: 'InventoryItem.characteristic', fhirVersion: 'R5')]
class FHIRInventoryItemCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null characteristicType The characteristic that is being defined */
        #[NotBlank]
        public ?FHIRCodeableConcept $characteristicType = null,
        /** @var FHIRString|string|FHIRInteger|FHIRDecimal|FHIRBoolean|FHIRUrl|FHIRDateTime|FHIRQuantity|FHIRRange|FHIRRatio|FHIRAnnotation|FHIRAddress|FHIRDuration|FHIRCodeableConcept|null valueX The value of the attribute */
        #[NotBlank]
        public FHIRString|string|FHIRInteger|FHIRDecimal|FHIRBoolean|FHIRUrl|FHIRDateTime|FHIRQuantity|FHIRRange|FHIRRatio|FHIRAnnotation|FHIRAddress|FHIRDuration|FHIRCodeableConcept|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
