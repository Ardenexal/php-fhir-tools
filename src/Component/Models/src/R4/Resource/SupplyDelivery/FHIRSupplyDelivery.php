<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/SupplyDelivery
 * @description Record of delivery of what is supplied.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'SupplyDelivery',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/SupplyDelivery',
	fhirVersion: 'R4',
)]
class FHIRSupplyDelivery extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier External identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> basedOn Fulfills plan, proposal or order */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> partOf Part of referenced event */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSupplyDeliveryStatusType status in-progress | completed | abandoned | entered-in-error */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSupplyDeliveryStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference patient Patient for whom the item is supplied */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type Category of dispense event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSupplyDeliverySuppliedItem suppliedItem The item that is delivered or supplied */
		public ?FHIRSupplyDeliverySuppliedItem $suppliedItem = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTiming occurrenceX When event occurred */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|FHIRTiming|null $occurrenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference supplier Dispenser */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $supplier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference destination Where the Supply was sent */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $destination = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> receiver Who collected the Supply */
		public array $receiver = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
