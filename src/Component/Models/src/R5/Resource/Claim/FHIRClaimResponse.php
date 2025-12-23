<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/ClaimResponse
 * @description This resource provides the adjudication details from the processing of a Claim resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'ClaimResponse', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/ClaimResponse', fhirVersion: 'R5')]
class FHIRClaimResponse extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Business Identifier for a claim response */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> traceNumber Number for tracking */
		public array $traceNumber = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRFinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRFinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type More granular claim type */
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime created Response creation date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDateTime $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference insurer Party responsible for reimbursement */
		public ?FHIRReference $insurer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference requestor Party responsible for the claim */
		public ?FHIRReference $requestor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference request Id of resource triggering adjudication */
		public ?FHIRReference $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimProcessingCodesType outcome queued | complete | error | partial */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRClaimProcessingCodesType $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept decision Result of the adjudication */
		public ?FHIRCodeableConcept $decision = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string disposition Disposition Message */
		public FHIRString|string|null $disposition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string preAuthRef Preauthorization reference */
		public FHIRString|string|null $preAuthRef = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod preAuthPeriod Preauthorization reference effective period */
		public ?FHIRPeriod $preAuthPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseEvent> event Event information */
		public array $event = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept payeeType Party to be paid any benefits payable */
		public ?FHIRCodeableConcept $payeeType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> encounter Encounters associated with the listed treatments */
		public array $encounter = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept diagnosisRelatedGroup Package billing code */
		public ?FHIRCodeableConcept $diagnosisRelatedGroup = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseItem> item Adjudication for claim line items */
		public array $item = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseAddItem> addItem Insurer added line items */
		public array $addItem = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseItemAdjudication> adjudication Header-level adjudication */
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseTotal> total Adjudication totals */
		public array $total = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponsePayment payment Payment Details */
		public ?FHIRClaimResponsePayment $payment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept fundsReserve Funds reserved status */
		public ?FHIRCodeableConcept $fundsReserve = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept formCode Printed form identifier */
		public ?FHIRCodeableConcept $formCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment form Printed reference or actual form */
		public ?FHIRAttachment $form = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseProcessNote> processNote Note concerning adjudication */
		public array $processNote = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> communicationRequest Request for additional information */
		public array $communicationRequest = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseInsurance> insurance Patient insurance information */
		public array $insurance = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClaimResponseError> error Processing errors */
		public array $error = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
