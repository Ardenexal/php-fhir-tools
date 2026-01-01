<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRClaimProcessingCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRFinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/PaymentReconciliation',
    fhirVersion: 'R4',
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
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
        /** @var FHIRClaimProcessingCodesType|null outcome queued | complete | error | partial */
        public ?FHIRClaimProcessingCodesType $outcome = null,
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
