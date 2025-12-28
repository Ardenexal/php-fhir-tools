<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The actual design of the device or software version running on the device.
 */
#[FHIRBackboneElement(parentResource: 'Device', elementPath: 'Device.version', fhirVersion: 'R4B')]
class FHIRDeviceVersion extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type The type of the device version, e.g. manufacturer, approved, internal */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRIdentifier|null component A single component of the device version */
        public ?\FHIRIdentifier $component = null,
        /** @var FHIRString|string|null value The version text */
        #[NotBlank]
        public \FHIRString|string|null $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
