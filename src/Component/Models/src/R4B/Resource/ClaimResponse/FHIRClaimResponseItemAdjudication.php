<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description If this item is a group then the values here are a summary of the adjudication of the detail items. If this item is a simple product or service then this is the result of the adjudication of this item.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.item.adjudication', fhirVersion: 'R4B')]
class FHIRClaimResponseItemAdjudication extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
        /** @var FHIRDecimal|null value Non-monetary value */
        public ?FHIRDecimal $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
