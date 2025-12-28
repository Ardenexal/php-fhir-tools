<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description This represents the manufacturer's name of the device as provided by the device, from a UDI label, or by a person describing the Device.  This typically would be used when a person provides the name(s) or when the device represents one of the names available from DeviceDefinition.
 */
#[FHIRBackboneElement(parentResource: 'Device', elementPath: 'Device.name', fhirVersion: 'R5')]
class FHIRDeviceName extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null value The term that names the device */
        #[NotBlank]
        public \FHIRString|string|null $value = null,
        /** @var FHIRDeviceNameTypeType|null type registered-name | user-friendly-name | patient-reported-name */
        #[NotBlank]
        public ?\FHIRDeviceNameTypeType $type = null,
        /** @var FHIRBoolean|null display The preferred device name */
        public ?\FHIRBoolean $display = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
