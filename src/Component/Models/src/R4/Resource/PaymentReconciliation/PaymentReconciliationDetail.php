<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\PaymentReconciliation;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Distribution of the payment amount for a previously acknowledged payable.
 */
#[FHIRBackboneElement(parentResource: 'PaymentReconciliation', elementPath: 'PaymentReconciliation.detail', fhirVersion: 'R4')]
class PaymentReconciliationDetail extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Identifier|null identifier Business identifier of the payment detail */
        public ?Identifier $identifier = null,
        /** @var Identifier|null predecessor Business identifier of the prior payment detail */
        public ?Identifier $predecessor = null,
        /** @var CodeableConcept|null type Category of payment */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var Reference|null request Request giving rise to the payment */
        public ?Reference $request = null,
        /** @var Reference|null submitter Submitter of the request */
        public ?Reference $submitter = null,
        /** @var Reference|null response Response committing to a payment */
        public ?Reference $response = null,
        /** @var DatePrimitive|null date Date of commitment to pay */
        public ?DatePrimitive $date = null,
        /** @var Reference|null responsible Contact for the response */
        public ?Reference $responsible = null,
        /** @var Reference|null payee Recipient of the payment */
        public ?Reference $payee = null,
        /** @var Money|null amount Amount allocated to this payable */
        public ?Money $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
