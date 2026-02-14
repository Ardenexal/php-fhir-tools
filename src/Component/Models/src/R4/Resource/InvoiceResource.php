<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/Invoice
 * @description Invoice containing collected ChargeItems from an Account with calculated individual and total price for Billing purpose.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Invoice', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Invoice', fhirVersion: 'R4')]
class InvoiceResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifier for item */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\InvoiceStatusType status draft | issued | balanced | cancelled | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\InvoiceStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string cancelledReason Reason for cancellation of this Invoice */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $cancelledReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Type of Invoice */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject Recipient(s) of goods and services */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference recipient Recipient of this invoice */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $recipient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive date Invoice date / posting date */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $date = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Invoice\InvoiceParticipant> participant Participant in creation of this Invoice */
		public array $participant = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference issuer Issuing Organization of Invoice */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $issuer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference account Account that is being balanced */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $account = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Invoice\InvoiceLineItem> lineItem Line items of this Invoice */
		public array $lineItem = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Invoice\InvoiceLineItemPriceComponent> totalPriceComponent Components of Invoice total */
		public array $totalPriceComponent = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money totalNet Net total of this Invoice */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $totalNet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money totalGross Gross total of this Invoice */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $totalGross = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive paymentTerms Payment details */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $paymentTerms = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Comments made about the invoice */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
