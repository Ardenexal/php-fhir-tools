<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDeviceNameTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A name given to the device to identify it.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.deviceName', fhirVersion: 'R4')]
class FHIRDeviceDefinitionDeviceName extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name The name of the device */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var FHIRDeviceNameTypeType|null type udi-label-name | user-friendly-name | patient-reported-name | manufacturer-name | model-name | other */
        #[NotBlank]
        public ?FHIRDeviceNameTypeType $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
