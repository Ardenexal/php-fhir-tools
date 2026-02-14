<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/ChargeItem
 * @description The resource ChargeItem describes the provision of healthcare provider products for a certain patient, therefore referring not only to the product, but containing in addition details of the provision, like date, time, amounts and participating organizations and persons. Main Usage of the ChargeItem is to enable the billing process and internal cost allocation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'ChargeItem', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/ChargeItem', fhirVersion: 'R4')]
class ChargeItemResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive> definitionUri Defining information about the code of this charge item */
		public array $definitionUri = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive> definitionCanonical Resource defining the code of this ChargeItem */
		public array $definitionCanonical = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ChargeItemStatusType status planned | billable | not-billable | aborted | billed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ChargeItemStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> partOf Part of referenced ChargeItem */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code A code that identifies the charge, like a billing code */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject Individual service was done for/to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference context Encounter / Episode associated with event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $context = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing occurrenceX When the charged service was applied */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing|null $occurrenceX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ChargeItem\ChargeItemPerformer> performer Who performed charged service */
		public array $performer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference performingOrganization Organization providing the charged service */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $performingOrganization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference requestingOrganization Organization requesting the charged service */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $requestingOrganization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference costCenter Organization that has ownership of the (potential, future) revenue */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $costCenter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity quantity Quantity of which the charge item has been serviced */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> bodysite Anatomical location, if relevant */
		public array $bodysite = [],
		/** @var null|float factorOverride Factor overriding the associated rules */
		public ?float $factorOverride = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money priceOverride Price overriding the associated rules */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $priceOverride = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string overrideReason Reason for overriding the list price/factor */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $overrideReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference enterer Individual who was entering */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $enterer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive enteredDate Date the charge item was entered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $enteredDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> reason Why was the charged  service rendered? */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> service Which rendered service is being charged? */
		public array $service = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept productX Product charged */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|null $productX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> account Account to place this charge */
		public array $account = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Comments made about the ChargeItem */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> supportingInformation Further information supporting this charge */
		public array $supportingInformation = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
