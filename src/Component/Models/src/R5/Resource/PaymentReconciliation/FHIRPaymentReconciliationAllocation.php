<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Distribution of the payment amount for a previously acknowledged payable.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PaymentReconciliation', elementPath: 'PaymentReconciliation.allocation', fhirVersion: 'R5')]
class FHIRPaymentReconciliationAllocation extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Business identifier of the payment detail */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRIdentifier|null predecessor Business identifier of the prior payment detail */
        public ?FHIRIdentifier $predecessor = null,
        /** @var FHIRReference|null target Subject of the payment */
        public ?FHIRReference $target = null,
        /** @var FHIRString|string|FHIRIdentifier|FHIRPositiveInt|null targetItemX Sub-element of the subject */
        public FHIRString|string|FHIRIdentifier|FHIRPositiveInt|null $targetItemX = null,
        /** @var FHIRReference|null encounter Applied-to encounter */
        public ?FHIRReference $encounter = null,
        /** @var FHIRReference|null account Applied-to account */
        public ?FHIRReference $account = null,
        /** @var FHIRCodeableConcept|null type Category of payment */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRReference|null submitter Submitter of the request */
        public ?FHIRReference $submitter = null,
        /** @var FHIRReference|null response Response committing to a payment */
        public ?FHIRReference $response = null,
        /** @var FHIRDate|null date Date of commitment to pay */
        public ?FHIRDate $date = null,
        /** @var FHIRReference|null responsible Contact for the response */
        public ?FHIRReference $responsible = null,
        /** @var FHIRReference|null payee Recipient of the payment */
        public ?FHIRReference $payee = null,
        /** @var FHIRMoney|null amount Amount allocated to this payable */
        public ?FHIRMoney $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
