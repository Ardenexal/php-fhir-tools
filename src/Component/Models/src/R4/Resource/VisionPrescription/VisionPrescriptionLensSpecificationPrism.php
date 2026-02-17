<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\VisionPrescription;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\VisionBaseType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Allows for adjustment on two axis.
 */
#[FHIRBackboneElement(parentResource: 'VisionPrescription', elementPath: 'VisionPrescription.lensSpecification.prism', fhirVersion: 'R4')]
class VisionPrescriptionLensSpecificationPrism extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var float|null amount Amount of adjustment */
        #[NotBlank]
        public ?float $amount = null,
        /** @var VisionBaseType|null base up | down | in | out */
        #[NotBlank]
        public ?VisionBaseType $base = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
