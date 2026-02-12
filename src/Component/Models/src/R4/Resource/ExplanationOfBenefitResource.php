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
class ExplanationOfBenefitResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifier for the resource */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ExplanationOfBenefitStatusType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ExplanationOfBenefitStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Category or discipline */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept subType More granular claim type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $subType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimUseType use claim | preauthorization | predetermination */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference patient The recipient of the products and services */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period billablePeriod Relevant time frame for the claim */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $billablePeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive created Response creation date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference enterer Author of the claim */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $enterer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference insurer Party responsible for reimbursement */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $insurer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference provider Party responsible for the claim */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $provider = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept priority Desired processing urgency */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept fundsReserveRequested For whom to reserve funds */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $fundsReserveRequested = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept fundsReserve Funds reserved status */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $fundsReserve = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitRelated> related Prior or corollary claims */
		public array $related = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference prescription Prescription authorizing services or products */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $prescription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference originalPrescription Original prescription if superceded by fulfiller */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $originalPrescription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitPayee payee Recipient of benefits payable */
		public ?ExplanationOfBenefit\ExplanationOfBenefitPayee $payee = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference referral Treatment Referral */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $referral = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference facility Servicing Facility */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $facility = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference claim Claim reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $claim = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference claimResponse Claim response reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $claimResponse = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType outcome queued | complete | error | partial */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string disposition Disposition Message */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $disposition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> preAuthRef Preauthorization reference */
		public array $preAuthRef = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period> preAuthRefPeriod Preauthorization in-effect period */
		public array $preAuthRefPeriod = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitCareTeam> careTeam Care Team members */
		public array $careTeam = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitSupportingInfo> supportingInfo Supporting information */
		public array $supportingInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitDiagnosis> diagnosis Pertinent diagnosis information */
		public array $diagnosis = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitProcedure> procedure Clinical procedures performed */
		public array $procedure = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive precedence Precedence (primary, secondary, etc.) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive $precedence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitInsurance> insurance Patient insurance information */
		public array $insurance = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitAccident accident Details of the event */
		public ?ExplanationOfBenefit\ExplanationOfBenefitAccident $accident = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItem> item Product or service provided */
		public array $item = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitAddItem> addItem Insurer added line items */
		public array $addItem = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemAdjudication> adjudication Header-level adjudication */
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitTotal> total Adjudication totals */
		public array $total = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitPayment payment Payment Details */
		public ?ExplanationOfBenefit\ExplanationOfBenefitPayment $payment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept formCode Printed form identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $formCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment form Printed reference or actual form */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment $form = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitProcessNote> processNote Note concerning adjudication */
		public array $processNote = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period benefitPeriod When the benefits are applicable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $benefitPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitBenefitBalance> benefitBalance Balance by Benefit Category */
		public array $benefitBalance = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
