<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Static or essentially fixed characteristics or features of this kind of device that are otherwise not captured in more specific attributes, e.g., time or timing attributes, resolution, accuracy, and physical attributes.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.property', fhirVersion: 'R5')]
class FHIRDeviceDefinitionProperty extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
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
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|FHIRCodeableConcept|FHIRString|string|FHIRBoolean|FHIRInteger|FHIRRange|FHIRAttachment|null valueX Value of the property */
        #[NotBlank]
        public \FHIRQuantity|\FHIRCodeableConcept|\FHIRString|string|\FHIRBoolean|\FHIRInteger|\FHIRRange|\FHIRAttachment|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
