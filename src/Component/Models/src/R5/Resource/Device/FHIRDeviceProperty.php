<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Static or essentially fixed characteristics or features of the device (e.g., time or timing attributes, resolution, accuracy, intended use or instructions for use, and physical attributes) that are not otherwise captured in more specific attributes.
 */
#[FHIRBackboneElement(parentResource: 'Device', elementPath: 'Device.property', fhirVersion: 'R5')]
class FHIRDeviceProperty extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Code that specifies the property being represented */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|FHIRCodeableConcept|FHIRString|string|FHIRBoolean|FHIRInteger|FHIRRange|FHIRAttachment|null valueX Value of the property */
        #[NotBlank]
        public FHIRQuantity|FHIRCodeableConcept|FHIRString|string|FHIRBoolean|FHIRInteger|FHIRRange|FHIRAttachment|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
