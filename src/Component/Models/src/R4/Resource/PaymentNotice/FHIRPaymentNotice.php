<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/PaymentNotice
 *
 * @description This resource provides the status of the payment for goods and services rendered, and the request and response resource references.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'PaymentNotice', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/PaymentNotice', fhirVersion: 'R4')]
class FHIRPaymentNotice extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business Identifier for the payment noctice */
        public array $identifier = [],
        /** @var FHIRFinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?\FHIRFinancialResourceStatusCodesType $status = null,
        /** @var FHIRReference|null request Request reference */
        public ?\FHIRReference $request = null,
        /** @var FHIRReference|null response Response reference */
        public ?\FHIRReference $response = null,
        /** @var FHIRDateTime|null created Creation date */
        #[NotBlank]
        public ?\FHIRDateTime $created = null,
        /** @var FHIRReference|null provider Responsible practitioner */
        public ?\FHIRReference $provider = null,
        /** @var FHIRReference|null payment Payment reference */
        #[NotBlank]
        public ?\FHIRReference $payment = null,
        /** @var FHIRDate|null paymentDate Payment or clearing date */
        public ?\FHIRDate $paymentDate = null,
        /** @var FHIRReference|null payee Party being paid */
        public ?\FHIRReference $payee = null,
        /** @var FHIRReference|null recipient Party being notified */
        #[NotBlank]
        public ?\FHIRReference $recipient = null,
        /** @var FHIRMoney|null amount Monetary amount of the payment */
        #[NotBlank]
        public ?\FHIRMoney $amount = null,
        /** @var FHIRCodeableConcept|null paymentStatus Issued or cleared Status of the payment */
        public ?\FHIRCodeableConcept $paymentStatus = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
