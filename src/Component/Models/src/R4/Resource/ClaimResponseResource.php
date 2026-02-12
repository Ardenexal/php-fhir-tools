<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/ClaimResponse
 * @description This resource provides the adjudication details from the processing of a Claim resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'ClaimResponse', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/ClaimResponse', fhirVersion: 'R4')]
class ClaimResponseResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifier for a claim response */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type More granular claim type */
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive created Response creation date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference insurer Party responsible for reimbursement */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $insurer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference requestor Party responsible for the claim */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $requestor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference request Id of resource triggering adjudication */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType outcome queued | complete | error | partial */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string disposition Disposition Message */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $disposition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string preAuthRef Preauthorization reference */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $preAuthRef = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period preAuthPeriod Preauthorization reference effective period */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $preAuthPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept payeeType Party to be paid any benefits payable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $payeeType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseItem> item Adjudication for claim line items */
		public array $item = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseAddItem> addItem Insurer added line items */
		public array $addItem = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseItemAdjudication> adjudication Header-level adjudication */
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseTotal> total Adjudication totals */
		public array $total = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponsePayment payment Payment Details */
		public ?ClaimResponse\ClaimResponsePayment $payment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept fundsReserve Funds reserved status */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $fundsReserve = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept formCode Printed form identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $formCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment form Printed reference or actual form */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment $form = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseProcessNote> processNote Note concerning adjudication */
		public array $processNote = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> communicationRequest Request for additional information */
		public array $communicationRequest = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseInsurance> insurance Patient insurance information */
		public array $insurance = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseError> error Processing errors */
		public array $error = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
