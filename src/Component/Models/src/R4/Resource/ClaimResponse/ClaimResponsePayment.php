<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Payment details for the adjudication of the claim.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.payment', fhirVersion: 'R4')]
class ClaimResponsePayment extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Partial or complete payment */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var Money|null adjustment Payment adjustment for non-claim issues */
        public ?Money $adjustment = null,
        /** @var CodeableConcept|null adjustmentReason Explanation for the adjustment */
        public ?CodeableConcept $adjustmentReason = null,
        /** @var DatePrimitive|null date Expected date of payment */
        public ?DatePrimitive $date = null,
        /** @var Money|null amount Payable amount after adjustment */
        #[NotBlank]
        public ?Money $amount = null,
        /** @var Identifier|null identifier Business identifier for the payment */
        public ?Identifier $identifier = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
