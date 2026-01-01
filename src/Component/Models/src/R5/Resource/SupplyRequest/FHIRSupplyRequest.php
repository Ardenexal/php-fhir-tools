<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/SupplyRequest
 * @description A record of a request to deliver a medication, substance or device used in the healthcare setting to a particular destination for a particular person or organization.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'SupplyRequest', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/SupplyRequest', fhirVersion: 'R5')]
class FHIRSupplyRequest extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Business Identifier for SupplyRequest */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSupplyRequestStatusType status draft | active | suspended + */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSupplyRequestStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> basedOn What other request is fulfilled by this supply request */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept category The kind of supply (central, non-stock, etc.) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference deliverFor The patient for who the supply request is for */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $deliverFor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference item Medication, Substance, or Device requested to be supplied */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference $item = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity quantity The requested amount of the item indicated */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSupplyRequestParameter> parameter Ordered item details */
		public array $parameter = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming occurrenceX When the request should be fulfilled */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming|null $occurrenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime authoredOn When the request was made */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $authoredOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference requester Individual making the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $requester = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> supplier Who is intended to fulfill the request */
		public array $supplier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> reason The reason why the supply item was requested */
		public array $reason = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference deliverFrom The origin of the supply */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $deliverFrom = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference deliverTo The destination of the supply */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $deliverTo = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
