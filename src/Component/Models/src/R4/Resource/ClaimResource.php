<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/Claim
 * @description A provider issued list of professional services and products which have been provided, or are to be provided, to a patient which is sent to an insurer for reimbursement.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Claim', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Claim', fhirVersion: 'R4')]
class ClaimResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifier for claim */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType $status = null,
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive created Resource creation date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference enterer Author of the claim */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $enterer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference insurer Target */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $insurer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference provider Party responsible for the claim */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $provider = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept priority Desired processing ugency */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept fundsReserve For whom to reserve funds */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $fundsReserve = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimRelated> related Prior or corollary claims */
		public array $related = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference prescription Prescription authorizing services and products */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $prescription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference originalPrescription Original prescription if superseded by fulfiller */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $originalPrescription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimPayee payee Recipient of benefits payable */
		public ?Claim\ClaimPayee $payee = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference referral Treatment referral */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $referral = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference facility Servicing facility */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $facility = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimCareTeam> careTeam Members of the care team */
		public array $careTeam = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimSupportingInfo> supportingInfo Supporting information */
		public array $supportingInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimDiagnosis> diagnosis Pertinent diagnosis information */
		public array $diagnosis = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimProcedure> procedure Clinical procedures performed */
		public array $procedure = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimInsurance> insurance Patient insurance information */
		public array $insurance = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimAccident accident Details of the event */
		public ?Claim\ClaimAccident $accident = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimItem> item Product or service provided */
		public array $item = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money total Total claim cost */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $total = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
