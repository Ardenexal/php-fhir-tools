<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Allows for adjustment on two axis.
 */
#[FHIRBackboneElement(parentResource: 'VisionPrescription', elementPath: 'VisionPrescription.lensSpecification.prism', fhirVersion: 'R4B')]
class FHIRVisionPrescriptionLensSpecificationPrism extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
