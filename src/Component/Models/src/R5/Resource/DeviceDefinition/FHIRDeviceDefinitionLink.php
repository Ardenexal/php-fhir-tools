<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An associated device, attached to, used with, communicating with or linking a previous or new device model to the focal device.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.link', fhirVersion: 'R5')]
class FHIRDeviceDefinitionLink extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCoding|null relation The type indicates the relationship of the related device to the device instance */
        #[NotBlank]
        public ?FHIRCoding $relation = null,
        /** @var FHIRCodeableReference|null relatedDevice A reference to the linked device */
        #[NotBlank]
        public ?FHIRCodeableReference $relatedDevice = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
