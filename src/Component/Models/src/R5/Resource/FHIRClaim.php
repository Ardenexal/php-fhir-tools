<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/Claim
 * @description A provider issued list of professional services and products which have been provided, or are to be provided, to a patient which is sent to an insurer for reimbursement.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Claim', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Claim', fhirVersion: 'R5')]
class FHIRClaim extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Business Identifier for claim */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> traceNumber Number for tracking */
		public array $traceNumber = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRFinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRFinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Category or discipline */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept subType More granular claim type */
		public ?FHIRCodeableConcept $subType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUseType use claim | preauthorization | predetermination */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference patient The recipient of the products and services */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod billablePeriod Relevant time frame for the claim */
		public ?FHIRPeriod $billablePeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime created Resource creation date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDateTime $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference enterer Author of the claim */
		public ?FHIRReference $enterer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference insurer Target */
		public ?FHIRReference $insurer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference provider Party responsible for the claim */
		public ?FHIRReference $provider = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept priority Desired processing urgency */
		public ?FHIRCodeableConcept $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept fundsReserve For whom to reserve funds */
		public ?FHIRCodeableConcept $fundsReserve = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimRelated> related Prior or corollary claims */
		public array $related = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference prescription Prescription authorizing services and products */
		public ?FHIRReference $prescription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference originalPrescription Original prescription if superseded by fulfiller */
		public ?FHIRReference $originalPrescription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimPayee payee Recipient of benefits payable */
		public ?FHIRClaimPayee $payee = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference referral Treatment referral */
		public ?FHIRReference $referral = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> encounter Encounters associated with the listed treatments */
		public array $encounter = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference facility Servicing facility */
		public ?FHIRReference $facility = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept diagnosisRelatedGroup Package billing code */
		public ?FHIRCodeableConcept $diagnosisRelatedGroup = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimEvent> event Event information */
		public array $event = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimCareTeam> careTeam Members of the care team */
		public array $careTeam = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimSupportingInfo> supportingInfo Supporting information */
		public array $supportingInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimDiagnosis> diagnosis Pertinent diagnosis information */
		public array $diagnosis = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimProcedure> procedure Clinical procedures performed */
		public array $procedure = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimInsurance> insurance Patient insurance information */
		public array $insurance = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimAccident accident Details of the event */
		public ?FHIRClaimAccident $accident = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney patientPaid Paid by the patient */
		public ?FHIRMoney $patientPaid = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimItem> item Product or service provided */
		public array $item = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney total Total claim cost */
		public ?FHIRMoney $total = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
