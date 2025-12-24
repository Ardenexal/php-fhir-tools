<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A suite of codes indicating the cost category and associated amount which have been detailed in the policy and may have been  included on the health card.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Coverage', elementPath: 'Coverage.costToBeneficiary', fhirVersion: 'R4')]
class FHIRCoverageCostToBeneficiary extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Cost category */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|FHIRMoney|null valueX The amount or percentage due from the beneficiary */
        #[NotBlank]
        public FHIRQuantity|FHIRMoney|null $valueX = null,
        /** @var array<FHIRCoverageCostToBeneficiaryException> exception Exceptions for patient payments */
        public array $exception = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
