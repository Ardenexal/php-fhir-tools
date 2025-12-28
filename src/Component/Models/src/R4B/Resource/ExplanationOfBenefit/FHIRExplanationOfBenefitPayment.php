<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Payment details for the adjudication of the claim.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.payment', fhirVersion: 'R4B')]
class FHIRExplanationOfBenefitPayment extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Partial or complete payment */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRMoney|null adjustment Payment adjustment for non-claim issues */
        public ?\FHIRMoney $adjustment = null,
        /** @var FHIRCodeableConcept|null adjustmentReason Explanation for the variance */
        public ?\FHIRCodeableConcept $adjustmentReason = null,
        /** @var FHIRDate|null date Expected date of payment */
        public ?\FHIRDate $date = null,
        /** @var FHIRMoney|null amount Payable amount after adjustment */
        public ?\FHIRMoney $amount = null,
        /** @var FHIRIdentifier|null identifier Business identifier for the payment */
        public ?\FHIRIdentifier $identifier = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
