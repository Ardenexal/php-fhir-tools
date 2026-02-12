<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/SupplyRequest
 * @description A record of a request for a medication, substance or device used in the healthcare setting.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'SupplyRequest', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/SupplyRequest', fhirVersion: 'R4')]
class SupplyRequestResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifier for SupplyRequest */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\SupplyRequestStatusType status draft | active | suspended + */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\SupplyRequestStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept category The kind of supply (central, non-stock, etc.) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference itemX Medication, Substance, or Device requested to be supplied */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $itemX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity quantity The requested amount of the item indicated */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SupplyRequest\SupplyRequestParameter> parameter Ordered item details */
		public array $parameter = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing occurrenceX When the request should be fulfilled */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing|null $occurrenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive authoredOn When the request was made */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $authoredOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference requester Individual making the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $requester = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> supplier Who is intended to fulfill the request */
		public array $supplier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> reasonCode The reason why the supply item was requested */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> reasonReference The reason why the supply item was requested */
		public array $reasonReference = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference deliverFrom The origin of the supply */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $deliverFrom = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference deliverTo The destination of the supply */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $deliverTo = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
