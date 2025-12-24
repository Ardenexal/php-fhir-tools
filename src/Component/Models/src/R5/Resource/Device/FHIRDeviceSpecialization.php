<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication.
 */
#[FHIRBackboneElement(parentResource: 'Device', elementPath: 'Device.specialization', fhirVersion: 'R4B')]
class FHIRDeviceSpecialization extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null systemType The standard that is used to operate and communicate */
        #[NotBlank]
        public ?FHIRCodeableConcept $systemType = null,
        /** @var FHIRString|string|null version The version of the standard that is used to operate and communicate */
        public FHIRString|string|null $version = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
