<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimUseType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ExplanationOfBenefitStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitAccident;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitAddItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitBenefitBalance;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitCareTeam;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitDiagnosis;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitInsurance;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemAdjudication;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitPayee;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitPayment;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitProcedure;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitProcessNote;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitRelated;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitSupportingInfo;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitTotal;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/ExplanationOfBenefit',
    fhirVersion: 'R4',
)]
class ExplanationOfBenefitResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Business Identifier for the resource */
        public array $identifier = [],
        /** @var ExplanationOfBenefitStatusType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?ExplanationOfBenefitStatusType $status = null,
        /** @var CodeableConcept|null type Category or discipline */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null subType More granular claim type */
        public ?CodeableConcept $subType = null,
        /** @var ClaimUseType|null use claim | preauthorization | predetermination */
        #[NotBlank]
        public ?ClaimUseType $use = null,
        /** @var Reference|null patient The recipient of the products and services */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var Period|null billablePeriod Relevant time frame for the claim */
        public ?Period $billablePeriod = null,
        /** @var DateTimePrimitive|null created Response creation date */
        #[NotBlank]
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null enterer Author of the claim */
        public ?Reference $enterer = null,
        /** @var Reference|null insurer Party responsible for reimbursement */
        #[NotBlank]
        public ?Reference $insurer = null,
        /** @var Reference|null provider Party responsible for the claim */
        #[NotBlank]
        public ?Reference $provider = null,
        /** @var CodeableConcept|null priority Desired processing urgency */
        public ?CodeableConcept $priority = null,
        /** @var CodeableConcept|null fundsReserveRequested For whom to reserve funds */
        public ?CodeableConcept $fundsReserveRequested = null,
        /** @var CodeableConcept|null fundsReserve Funds reserved status */
        public ?CodeableConcept $fundsReserve = null,
        /** @var array<ExplanationOfBenefitRelated> related Prior or corollary claims */
        public array $related = [],
        /** @var Reference|null prescription Prescription authorizing services or products */
        public ?Reference $prescription = null,
        /** @var Reference|null originalPrescription Original prescription if superceded by fulfiller */
        public ?Reference $originalPrescription = null,
        /** @var ExplanationOfBenefitPayee|null payee Recipient of benefits payable */
        public ?ExplanationOfBenefitPayee $payee = null,
        /** @var Reference|null referral Treatment Referral */
        public ?Reference $referral = null,
        /** @var Reference|null facility Servicing Facility */
        public ?Reference $facility = null,
        /** @var Reference|null claim Claim reference */
        public ?Reference $claim = null,
        /** @var Reference|null claimResponse Claim response reference */
        public ?Reference $claimResponse = null,
        /** @var ClaimProcessingCodesType|null outcome queued | complete | error | partial */
        #[NotBlank]
        public ?ClaimProcessingCodesType $outcome = null,
        /** @var StringPrimitive|string|null disposition Disposition Message */
        public StringPrimitive|string|null $disposition = null,
        /** @var array<StringPrimitive|string> preAuthRef Preauthorization reference */
        public array $preAuthRef = [],
        /** @var array<Period> preAuthRefPeriod Preauthorization in-effect period */
        public array $preAuthRefPeriod = [],
        /** @var array<ExplanationOfBenefitCareTeam> careTeam Care Team members */
        public array $careTeam = [],
        /** @var array<ExplanationOfBenefitSupportingInfo> supportingInfo Supporting information */
        public array $supportingInfo = [],
        /** @var array<ExplanationOfBenefitDiagnosis> diagnosis Pertinent diagnosis information */
        public array $diagnosis = [],
        /** @var array<ExplanationOfBenefitProcedure> procedure Clinical procedures performed */
        public array $procedure = [],
        /** @var PositiveIntPrimitive|null precedence Precedence (primary, secondary, etc.) */
        public ?PositiveIntPrimitive $precedence = null,
        /** @var array<ExplanationOfBenefitInsurance> insurance Patient insurance information */
        public array $insurance = [],
        /** @var ExplanationOfBenefitAccident|null accident Details of the event */
        public ?ExplanationOfBenefitAccident $accident = null,
        /** @var array<ExplanationOfBenefitItem> item Product or service provided */
        public array $item = [],
        /** @var array<ExplanationOfBenefitAddItem> addItem Insurer added line items */
        public array $addItem = [],
        /** @var array<ExplanationOfBenefitItemAdjudication> adjudication Header-level adjudication */
        public array $adjudication = [],
        /** @var array<ExplanationOfBenefitTotal> total Adjudication totals */
        public array $total = [],
        /** @var ExplanationOfBenefitPayment|null payment Payment Details */
        public ?ExplanationOfBenefitPayment $payment = null,
        /** @var CodeableConcept|null formCode Printed form identifier */
        public ?CodeableConcept $formCode = null,
        /** @var Attachment|null form Printed reference or actual form */
        public ?Attachment $form = null,
        /** @var array<ExplanationOfBenefitProcessNote> processNote Note concerning adjudication */
        public array $processNote = [],
        /** @var Period|null benefitPeriod When the benefits are applicable */
        public ?Period $benefitPeriod = null,
        /** @var array<ExplanationOfBenefitBenefitBalance> benefitBalance Balance by Benefit Category */
        public array $benefitBalance = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
