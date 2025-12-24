<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ClaimResponse
 *
 * @description This resource provides the adjudication details from the processing of a Claim resource.
 */
#[FhirResource(type: 'ClaimResponse', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/ClaimResponse', fhirVersion: 'R5')]
class FHIRClaimResponse extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business Identifier for a claim response */
        public array $identifier = [],
        /** @var array<FHIRIdentifier> traceNumber Number for tracking */
        public array $traceNumber = [],
        /** @var FHIRFinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FHIRFinancialResourceStatusCodesType $status = null,
        /** @var FHIRCodeableConcept|null type More granular claim type */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null subType More granular claim type */
        public ?FHIRCodeableConcept $subType = null,
        /** @var FHIRUseType|null use claim | preauthorization | predetermination */
        #[NotBlank]
        public ?FHIRUseType $use = null,
        /** @var FHIRReference|null patient The recipient of the products and services */
        #[NotBlank]
        public ?FHIRReference $patient = null,
        /** @var FHIRDateTime|null created Response creation date */
        #[NotBlank]
        public ?FHIRDateTime $created = null,
        /** @var FHIRReference|null insurer Party responsible for reimbursement */
        public ?FHIRReference $insurer = null,
        /** @var FHIRReference|null requestor Party responsible for the claim */
        public ?FHIRReference $requestor = null,
        /** @var FHIRReference|null request Id of resource triggering adjudication */
        public ?FHIRReference $request = null,
        /** @var FHIRClaimProcessingCodesType|null outcome queued | complete | error | partial */
        #[NotBlank]
        public ?FHIRClaimProcessingCodesType $outcome = null,
        /** @var FHIRCodeableConcept|null decision Result of the adjudication */
        public ?FHIRCodeableConcept $decision = null,
        /** @var FHIRString|string|null disposition Disposition Message */
        public FHIRString|string|null $disposition = null,
        /** @var FHIRString|string|null preAuthRef Preauthorization reference */
        public FHIRString|string|null $preAuthRef = null,
        /** @var FHIRPeriod|null preAuthPeriod Preauthorization reference effective period */
        public ?FHIRPeriod $preAuthPeriod = null,
        /** @var array<FHIRClaimResponseEvent> event Event information */
        public array $event = [],
        /** @var FHIRCodeableConcept|null payeeType Party to be paid any benefits payable */
        public ?FHIRCodeableConcept $payeeType = null,
        /** @var array<FHIRReference> encounter Encounters associated with the listed treatments */
        public array $encounter = [],
        /** @var FHIRCodeableConcept|null diagnosisRelatedGroup Package billing code */
        public ?FHIRCodeableConcept $diagnosisRelatedGroup = null,
        /** @var array<FHIRClaimResponseItem> item Adjudication for claim line items */
        public array $item = [],
        /** @var array<FHIRClaimResponseAddItem> addItem Insurer added line items */
        public array $addItem = [],
        /** @var array<FHIRClaimResponseItemAdjudication> adjudication Header-level adjudication */
        public array $adjudication = [],
        /** @var array<FHIRClaimResponseTotal> total Adjudication totals */
        public array $total = [],
        /** @var FHIRClaimResponsePayment|null payment Payment Details */
        public ?FHIRClaimResponsePayment $payment = null,
        /** @var FHIRCodeableConcept|null fundsReserve Funds reserved status */
        public ?FHIRCodeableConcept $fundsReserve = null,
        /** @var FHIRCodeableConcept|null formCode Printed form identifier */
        public ?FHIRCodeableConcept $formCode = null,
        /** @var FHIRAttachment|null form Printed reference or actual form */
        public ?FHIRAttachment $form = null,
        /** @var array<FHIRClaimResponseProcessNote> processNote Note concerning adjudication */
        public array $processNote = [],
        /** @var array<FHIRReference> communicationRequest Request for additional information */
        public array $communicationRequest = [],
        /** @var array<FHIRClaimResponseInsurance> insurance Patient insurance information */
        public array $insurance = [],
        /** @var array<FHIRClaimResponseError> error Processing errors */
        public array $error = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
