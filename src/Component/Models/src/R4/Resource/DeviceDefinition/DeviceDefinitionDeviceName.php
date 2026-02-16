<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceNameTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A name given to the device to identify it.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.deviceName', fhirVersion: 'R4')]
class DeviceDefinitionDeviceName extends BackboneElement
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
