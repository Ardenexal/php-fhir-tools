<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDeviceNameTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description This represents the manufacturer's name of the device as provided by the device, from a UDI label, or by a person describing the Device.  This typically would be used when a person provides the name(s) or when the device represents one of the names available from DeviceDefinition.
 */
#[FHIRBackboneElement(parentResource: 'Device', elementPath: 'Device.deviceName', fhirVersion: 'R4B')]
class FHIRDeviceDeviceName extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name The name that identifies the device */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var FHIRDeviceNameTypeType|null type udi-label-name | user-friendly-name | patient-reported-name | manufacturer-name | model-name | other */
        #[NotBlank]
        public ?FHIRDeviceNameTypeType $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
