<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/PaymentReconciliation
 *
 * @description This resource provides the details including amount of a payment and allocates the payment items being paid.
 */
#[FhirResource(
    type: 'PaymentReconciliation',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/PaymentReconciliation',
    fhirVersion: 'R5',
)]
class FHIRPaymentReconciliation extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business Identifier for a payment reconciliation */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null type Category of payment */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRFinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FHIRFinancialResourceStatusCodesType $status = null,
        /** @var FHIRCodeableConcept|null kind Workflow originating payment */
        public ?FHIRCodeableConcept $kind = null,
        /** @var FHIRPeriod|null period Period covered */
        public ?FHIRPeriod $period = null,
        /** @var FHIRDateTime|null created Creation date */
        #[NotBlank]
        public ?FHIRDateTime $created = null,
        /** @var FHIRReference|null enterer Who entered the payment */
        public ?FHIRReference $enterer = null,
        /** @var FHIRCodeableConcept|null issuerType Nature of the source */
        public ?FHIRCodeableConcept $issuerType = null,
        /** @var FHIRReference|null paymentIssuer Party generating payment */
        public ?FHIRReference $paymentIssuer = null,
        /** @var FHIRReference|null request Reference to requesting resource */
        public ?FHIRReference $request = null,
        /** @var FHIRReference|null requestor Responsible practitioner */
        public ?FHIRReference $requestor = null,
        /** @var FHIRPaymentOutcomeType|null outcome queued | complete | error | partial */
        public ?FHIRPaymentOutcomeType $outcome = null,
        /** @var FHIRString|string|null disposition Disposition message */
        public FHIRString|string|null $disposition = null,
        /** @var FHIRDate|null date When payment issued */
        #[NotBlank]
        public ?FHIRDate $date = null,
        /** @var FHIRReference|null location Where payment collected */
        public ?FHIRReference $location = null,
        /** @var FHIRCodeableConcept|null method Payment instrument */
        public ?FHIRCodeableConcept $method = null,
        /** @var FHIRString|string|null cardBrand Type of card */
        public FHIRString|string|null $cardBrand = null,
        /** @var FHIRString|string|null accountNumber Digits for verification */
        public FHIRString|string|null $accountNumber = null,
        /** @var FHIRDate|null expirationDate Expiration year-month */
        public ?FHIRDate $expirationDate = null,
        /** @var FHIRString|string|null processor Processor name */
        public FHIRString|string|null $processor = null,
        /** @var FHIRString|string|null referenceNumber Check number or payment reference */
        public FHIRString|string|null $referenceNumber = null,
        /** @var FHIRString|string|null authorization Authorization number */
        public FHIRString|string|null $authorization = null,
        /** @var FHIRMoney|null tenderedAmount Amount offered by the issuer */
        public ?FHIRMoney $tenderedAmount = null,
        /** @var FHIRMoney|null returnedAmount Amount returned by the receiver */
        public ?FHIRMoney $returnedAmount = null,
        /** @var FHIRMoney|null amount Total amount of Payment */
        #[NotBlank]
        public ?FHIRMoney $amount = null,
        /** @var FHIRIdentifier|null paymentIdentifier Business identifier for the payment */
        public ?FHIRIdentifier $paymentIdentifier = null,
        /** @var array<FHIRPaymentReconciliationAllocation> allocation Settlement particulars */
        public array $allocation = [],
        /** @var FHIRCodeableConcept|null formCode Printed form identifier */
        public ?FHIRCodeableConcept $formCode = null,
        /** @var array<FHIRPaymentReconciliationProcessNote> processNote Note concerning processing */
        public array $processNote = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
