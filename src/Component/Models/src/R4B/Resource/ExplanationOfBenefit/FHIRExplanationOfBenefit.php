<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ExplanationOfBenefit
 *
 * @description This resource provides: the claim details; adjudication details from the processing of a Claim; and optionally account balance information, for informing the subscriber of the benefits provided.
 */
#[FhirResource(
    type: 'ExplanationOfBenefit',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ExplanationOfBenefit',
    fhirVersion: 'R4B',
)]
class FHIRExplanationOfBenefit extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business Identifier for the resource */
        public array $identifier = [],
        /** @var FHIRExplanationOfBenefitStatusType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FHIRExplanationOfBenefitStatusType $status = null,
        /** @var FHIRCodeableConcept|null type Category or discipline */
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
        /** @var FHIRPeriod|null billablePeriod Relevant time frame for the claim */
        public ?FHIRPeriod $billablePeriod = null,
        /** @var FHIRDateTime|null created Response creation date */
        #[NotBlank]
        public ?FHIRDateTime $created = null,
        /** @var FHIRReference|null enterer Author of the claim */
        public ?FHIRReference $enterer = null,
        /** @var FHIRReference|null insurer Party responsible for reimbursement */
        #[NotBlank]
        public ?FHIRReference $insurer = null,
        /** @var FHIRReference|null provider Party responsible for the claim */
        #[NotBlank]
        public ?FHIRReference $provider = null,
        /** @var FHIRCodeableConcept|null priority Desired processing urgency */
        public ?FHIRCodeableConcept $priority = null,
        /** @var FHIRCodeableConcept|null fundsReserveRequested For whom to reserve funds */
        public ?FHIRCodeableConcept $fundsReserveRequested = null,
        /** @var FHIRCodeableConcept|null fundsReserve Funds reserved status */
        public ?FHIRCodeableConcept $fundsReserve = null,
        /** @var array<FHIRExplanationOfBenefitRelated> related Prior or corollary claims */
        public array $related = [],
        /** @var FHIRReference|null prescription Prescription authorizing services or products */
        public ?FHIRReference $prescription = null,
        /** @var FHIRReference|null originalPrescription Original prescription if superceded by fulfiller */
        public ?FHIRReference $originalPrescription = null,
        /** @var FHIRExplanationOfBenefitPayee|null payee Recipient of benefits payable */
        public ?FHIRExplanationOfBenefitPayee $payee = null,
        /** @var FHIRReference|null referral Treatment Referral */
        public ?FHIRReference $referral = null,
        /** @var FHIRReference|null facility Servicing Facility */
        public ?FHIRReference $facility = null,
        /** @var FHIRReference|null claim Claim reference */
        public ?FHIRReference $claim = null,
        /** @var FHIRReference|null claimResponse Claim response reference */
        public ?FHIRReference $claimResponse = null,
        /** @var FHIRRemittanceOutcomeType|null outcome queued | complete | error | partial */
        #[NotBlank]
        public ?FHIRRemittanceOutcomeType $outcome = null,
        /** @var FHIRString|string|null disposition Disposition Message */
        public FHIRString|string|null $disposition = null,
        /** @var array<FHIRString|string> preAuthRef Preauthorization reference */
        public array $preAuthRef = [],
        /** @var array<FHIRPeriod> preAuthRefPeriod Preauthorization in-effect period */
        public array $preAuthRefPeriod = [],
        /** @var array<FHIRExplanationOfBenefitCareTeam> careTeam Care Team members */
        public array $careTeam = [],
        /** @var array<FHIRExplanationOfBenefitSupportingInfo> supportingInfo Supporting information */
        public array $supportingInfo = [],
        /** @var array<FHIRExplanationOfBenefitDiagnosis> diagnosis Pertinent diagnosis information */
        public array $diagnosis = [],
        /** @var array<FHIRExplanationOfBenefitProcedure> procedure Clinical procedures performed */
        public array $procedure = [],
        /** @var FHIRPositiveInt|null precedence Precedence (primary, secondary, etc.) */
        public ?FHIRPositiveInt $precedence = null,
        /** @var array<FHIRExplanationOfBenefitInsurance> insurance Patient insurance information */
        public array $insurance = [],
        /** @var FHIRExplanationOfBenefitAccident|null accident Details of the event */
        public ?FHIRExplanationOfBenefitAccident $accident = null,
        /** @var array<FHIRExplanationOfBenefitItem> item Product or service provided */
        public array $item = [],
        /** @var array<FHIRExplanationOfBenefitAddItem> addItem Insurer added line items */
        public array $addItem = [],
        /** @var array<FHIRExplanationOfBenefitItemAdjudication> adjudication Header-level adjudication */
        public array $adjudication = [],
        /** @var array<FHIRExplanationOfBenefitTotal> total Adjudication totals */
        public array $total = [],
        /** @var FHIRExplanationOfBenefitPayment|null payment Payment Details */
        public ?FHIRExplanationOfBenefitPayment $payment = null,
        /** @var FHIRCodeableConcept|null formCode Printed form identifier */
        public ?FHIRCodeableConcept $formCode = null,
        /** @var FHIRAttachment|null form Printed reference or actual form */
        public ?FHIRAttachment $form = null,
        /** @var array<FHIRExplanationOfBenefitProcessNote> processNote Note concerning adjudication */
        public array $processNote = [],
        /** @var FHIRPeriod|null benefitPeriod When the benefits are applicable */
        public ?FHIRPeriod $benefitPeriod = null,
        /** @var array<FHIRExplanationOfBenefitBenefitBalance> benefitBalance Balance by Benefit Category */
        public array $benefitBalance = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
