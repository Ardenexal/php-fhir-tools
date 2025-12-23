<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/ExplanationOfBenefit
 * @description This resource provides: the claim details; adjudication details from the processing of a Claim; and optionally account balance information, for informing the subscriber of the benefits provided.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'ExplanationOfBenefit',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/ExplanationOfBenefit',
	fhirVersion: 'R4',
)]
class FHIRExplanationOfBenefit extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier Business Identifier for the resource */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitStatusType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRExplanationOfBenefitStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type Category or discipline */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept subType More granular claim type */
		public ?FHIRCodeableConcept $subType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUseType use claim | preauthorization | predetermination */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference patient The recipient of the products and services */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod billablePeriod Relevant time frame for the claim */
		public ?FHIRPeriod $billablePeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime created Response creation date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDateTime $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference enterer Author of the claim */
		public ?FHIRReference $enterer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference insurer Party responsible for reimbursement */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $insurer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference provider Party responsible for the claim */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $provider = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept priority Desired processing urgency */
		public ?FHIRCodeableConcept $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept fundsReserveRequested For whom to reserve funds */
		public ?FHIRCodeableConcept $fundsReserveRequested = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept fundsReserve Funds reserved status */
		public ?FHIRCodeableConcept $fundsReserve = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitRelated> related Prior or corollary claims */
		public array $related = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference prescription Prescription authorizing services or products */
		public ?FHIRReference $prescription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference originalPrescription Original prescription if superceded by fulfiller */
		public ?FHIRReference $originalPrescription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitPayee payee Recipient of benefits payable */
		public ?FHIRExplanationOfBenefitPayee $payee = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference referral Treatment Referral */
		public ?FHIRReference $referral = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference facility Servicing Facility */
		public ?FHIRReference $facility = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference claim Claim reference */
		public ?FHIRReference $claim = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference claimResponse Claim response reference */
		public ?FHIRReference $claimResponse = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRClaimProcessingCodesType outcome queued | complete | error | partial */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRClaimProcessingCodesType $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string disposition Disposition Message */
		public FHIRString|string|null $disposition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string> preAuthRef Preauthorization reference */
		public array $preAuthRef = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod> preAuthRefPeriod Preauthorization in-effect period */
		public array $preAuthRefPeriod = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitCareTeam> careTeam Care Team members */
		public array $careTeam = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitSupportingInfo> supportingInfo Supporting information */
		public array $supportingInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitDiagnosis> diagnosis Pertinent diagnosis information */
		public array $diagnosis = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitProcedure> procedure Clinical procedures performed */
		public array $procedure = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt precedence Precedence (primary, secondary, etc.) */
		public ?FHIRPositiveInt $precedence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitInsurance> insurance Patient insurance information */
		public array $insurance = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitAccident accident Details of the event */
		public ?FHIRExplanationOfBenefitAccident $accident = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitItem> item Product or service provided */
		public array $item = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitAddItem> addItem Insurer added line items */
		public array $addItem = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitItemAdjudication> adjudication Header-level adjudication */
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitTotal> total Adjudication totals */
		public array $total = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitPayment payment Payment Details */
		public ?FHIRExplanationOfBenefitPayment $payment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept formCode Printed form identifier */
		public ?FHIRCodeableConcept $formCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAttachment form Printed reference or actual form */
		public ?FHIRAttachment $form = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitProcessNote> processNote Note concerning adjudication */
		public array $processNote = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod benefitPeriod When the benefits are applicable */
		public ?FHIRPeriod $benefitPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExplanationOfBenefitBenefitBalance> benefitBalance Balance by Benefit Category */
		public array $benefitBalance = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
