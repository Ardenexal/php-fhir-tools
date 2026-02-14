<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Device;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceNameTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description This represents the manufacturer's name of the device as provided by the device, from a UDI label, or by a person describing the Device.  This typically would be used when a person provides the name(s) or when the device represents one of the names available from DeviceDefinition.
 */
#[FHIRBackboneElement(parentResource: 'Device', elementPath: 'Device.deviceName', fhirVersion: 'R4')]
class DeviceDeviceName extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null name The name of the device */
        #[NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var DeviceNameTypeType|null type udi-label-name | user-friendly-name | patient-reported-name | manufacturer-name | model-name | other */
        #[NotBlank]
        public ?DeviceNameTypeType $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
