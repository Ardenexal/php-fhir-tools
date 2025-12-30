<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRFinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRemittanceOutcomeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
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
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/PaymentReconciliation',
    fhirVersion: 'R4B',
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
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business Identifier for a payment reconciliation */
        public array $identifier = [],
        /** @var FHIRFinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FHIRFinancialResourceStatusCodesType $status = null,
        /** @var FHIRPeriod|null period Period covered */
        public ?FHIRPeriod $period = null,
        /** @var FHIRDateTime|null created Creation date */
        #[NotBlank]
        public ?FHIRDateTime $created = null,
        /** @var FHIRReference|null paymentIssuer Party generating payment */
        public ?FHIRReference $paymentIssuer = null,
        /** @var FHIRReference|null request Reference to requesting resource */
        public ?FHIRReference $request = null,
        /** @var FHIRReference|null requestor Responsible practitioner */
        public ?FHIRReference $requestor = null,
        /** @var FHIRRemittanceOutcomeType|null outcome queued | complete | error | partial */
        public ?FHIRRemittanceOutcomeType $outcome = null,
        /** @var FHIRString|string|null disposition Disposition message */
        public FHIRString|string|null $disposition = null,
        /** @var FHIRDate|null paymentDate When payment issued */
        #[NotBlank]
        public ?FHIRDate $paymentDate = null,
        /** @var FHIRMoney|null paymentAmount Total amount of Payment */
        #[NotBlank]
        public ?FHIRMoney $paymentAmount = null,
        /** @var FHIRIdentifier|null paymentIdentifier Business identifier for the payment */
        public ?FHIRIdentifier $paymentIdentifier = null,
        /** @var array<FHIRPaymentReconciliationDetail> detail Settlement particulars */
        public array $detail = [],
        /** @var FHIRCodeableConcept|null formCode Printed form identifier */
        public ?FHIRCodeableConcept $formCode = null,
        /** @var array<FHIRPaymentReconciliationProcessNote> processNote Note concerning processing */
        public array $processNote = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
