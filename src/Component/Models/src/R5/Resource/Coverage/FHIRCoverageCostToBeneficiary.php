<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;

/**
 * @description A suite of codes indicating the cost category and associated amount which have been detailed in the policy and may have been  included on the health card.
 */
#[FHIRBackboneElement(parentResource: 'Coverage', elementPath: 'Coverage.costToBeneficiary', fhirVersion: 'R5')]
class FHIRCoverageCostToBeneficiary extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
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
        /** @var FHIRCodeableConcept|null category Benefit classification */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null network In or out of network */
        public ?FHIRCodeableConcept $network = null,
        /** @var FHIRCodeableConcept|null unit Individual or family */
        public ?FHIRCodeableConcept $unit = null,
        /** @var FHIRCodeableConcept|null term Annual or lifetime */
        public ?FHIRCodeableConcept $term = null,
        /** @var FHIRQuantity|FHIRMoney|null valueX The amount or percentage due from the beneficiary */
        public FHIRQuantity|FHIRMoney|null $valueX = null,
        /** @var array<FHIRCoverageCostToBeneficiaryException> exception Exceptions for patient payments */
        public array $exception = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
