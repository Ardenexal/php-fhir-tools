<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/PaymentReconciliation
 * @description This resource provides the details including amount of a payment and allocates the payment items being paid.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'PaymentReconciliation',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/PaymentReconciliation',
	fhirVersion: 'R4',
)]
class PaymentReconciliationResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifier for a payment reconciliation */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Period covered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive created Creation date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference paymentIssuer Party generating payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $paymentIssuer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference request Reference to requesting resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference requestor Responsible practitioner */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $requestor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType outcome queued | complete | error | partial */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string disposition Disposition message */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $disposition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive paymentDate When payment issued */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $paymentDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money paymentAmount Total amount of Payment */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $paymentAmount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier paymentIdentifier Business identifier for the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $paymentIdentifier = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\PaymentReconciliation\PaymentReconciliationDetail> detail Settlement particulars */
		public array $detail = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept formCode Printed form identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $formCode = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\PaymentReconciliation\PaymentReconciliationProcessNote> processNote Note concerning processing */
		public array $processNote = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
