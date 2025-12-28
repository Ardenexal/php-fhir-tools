<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates who or what performed the event.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDispense', elementPath: 'DeviceDispense.performer', fhirVersion: 'R5')]
class FHIRDeviceDispensePerformer extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null function Who performed the dispense and what they did */
        public ?\FHIRCodeableConcept $function = null,
        /** @var FHIRReference|null actor Individual who was performing */
        #[NotBlank]
        public ?\FHIRReference $actor = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
