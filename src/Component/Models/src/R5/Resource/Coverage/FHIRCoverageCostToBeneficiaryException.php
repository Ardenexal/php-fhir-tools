<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A suite of codes indicating exceptions or reductions to patient costs and their effective periods.
 */
#[FHIRBackboneElement(parentResource: 'Coverage', elementPath: 'Coverage.costToBeneficiary.exception', fhirVersion: 'R5')]
class FHIRCoverageCostToBeneficiaryException extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Exception category */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRPeriod|null period The effective period of the exception */
        public ?FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
