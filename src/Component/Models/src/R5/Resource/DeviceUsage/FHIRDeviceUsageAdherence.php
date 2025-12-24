<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description This indicates how or if the device is being used.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DeviceUsage', elementPath: 'DeviceUsage.adherence', fhirVersion: 'R5')]
class FHIRDeviceUsageAdherence extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code always | never | sometimes */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var array<FHIRCodeableConcept> reason lost | stolen | prescribed | broken | burned | forgot */
        public array $reason = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
