<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies the standards, specifications, or formal guidances for the capabilities supported by the device. The device may be certified as conformant to these specifications e.g., communication, performance, process, measurement, or specialization standards.
 */
#[FHIRBackboneElement(parentResource: 'Device', elementPath: 'Device.conformsTo', fhirVersion: 'R5')]
class FHIRDeviceConformsTo extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null category Describes the common type of the standard, specification, or formal guidance.  communication | performance | measurement */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null specification Identifies the standard, specification, or formal guidance that the device adheres to */
        #[NotBlank]
        public ?FHIRCodeableConcept $specification = null,
        /** @var FHIRString|string|null version Specific form or variant of the standard */
        public FHIRString|string|null $version = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
