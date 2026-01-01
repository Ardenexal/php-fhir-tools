<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties.
 */
#[FHIRBackboneElement(parentResource: 'Device', elementPath: 'Device.property', fhirVersion: 'R4')]
class FHIRDeviceProperty extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Code that specifies the property DeviceDefinitionPropetyCode (Extensible) */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRQuantity> valueQuantity Property value as a quantity */
        public array $valueQuantity = [],
        /** @var array<FHIRCodeableConcept> valueCode Property value as a code, e.g., NTP4 (synced to NTP) */
        public array $valueCode = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
