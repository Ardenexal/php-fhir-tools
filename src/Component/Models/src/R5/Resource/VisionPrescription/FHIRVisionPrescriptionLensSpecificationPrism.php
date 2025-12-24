<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Allows for adjustment on two axis.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'VisionPrescription', elementPath: 'VisionPrescription.lensSpecification.prism', fhirVersion: 'R5')]
class FHIRVisionPrescriptionLensSpecificationPrism extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRDecimal|null amount Amount of adjustment */
        #[NotBlank]
        public ?FHIRDecimal $amount = null,
        /** @var FHIRVisionBaseType|null base up | down | in | out */
        #[NotBlank]
        public ?FHIRVisionBaseType $base = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
