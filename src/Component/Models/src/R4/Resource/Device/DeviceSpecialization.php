<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Device;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication.
 */
#[FHIRBackboneElement(parentResource: 'Device', elementPath: 'Device.specialization', fhirVersion: 'R4')]
class DeviceSpecialization extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null systemType The standard that is used to operate and communicate */
        #[NotBlank]
        public ?CodeableConcept $systemType = null,
        /** @var StringPrimitive|string|null version The version of the standard that is used to operate and communicate */
        public StringPrimitive|string|null $version = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
