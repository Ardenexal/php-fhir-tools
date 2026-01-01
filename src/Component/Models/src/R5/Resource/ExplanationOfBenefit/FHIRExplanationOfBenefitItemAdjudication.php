<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description If this item is a group then the values here are a summary of the adjudication of the detail items. If this item is a simple product or service then this is the result of the adjudication of this item.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.item.adjudication', fhirVersion: 'R5')]
class FHIRExplanationOfBenefitItemAdjudication extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null category Type of adjudication information */
        #[NotBlank]
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null reason Explanation of adjudication outcome */
        public ?FHIRCodeableConcept $reason = null,
        /** @var FHIRMoney|null amount Monetary amount */
        public ?FHIRMoney $amount = null,
        /** @var FHIRQuantity|null quantity Non-monitary value */
        public ?FHIRQuantity $quantity = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
