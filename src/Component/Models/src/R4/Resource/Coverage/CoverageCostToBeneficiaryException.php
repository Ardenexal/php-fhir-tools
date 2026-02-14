<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Coverage;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A suite of codes indicating exceptions or reductions to patient costs and their effective periods.
 */
#[FHIRBackboneElement(parentResource: 'Coverage', elementPath: 'Coverage.costToBeneficiary.exception', fhirVersion: 'R4')]
class CoverageCostToBeneficiaryException extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Exception category */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var Period|null period The effective period of the exception */
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
