<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/Observation
 * @description Measurements and simple assertions made about a patient, device or other subject.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Observation', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Observation', fhirVersion: 'R4')]
class ObservationResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifier for observation */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> basedOn Fulfills plan, proposal or order */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> partOf Part of referenced event */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationStatusType status registered | preliminary | final | amended + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> category Classification of  type of observation */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Type of observation (code / type) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject Who and/or what the observation is about */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> focus What the observation is about, when it is not about the subject of record */
		public array $focus = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference encounter Healthcare event during which this observation is made */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive effectiveX Clinically relevant time/time-period for observation */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive|null $effectiveX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive issued Date/Time this version was made available */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive $issued = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> performer Who is responsible for the observation */
		public array $performer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|bool|int|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4\DataType\SampledData|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period valueX Actual result */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|bool|int|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4\DataType\SampledData|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|null $valueX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept dataAbsentReason Why the result is missing */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $dataAbsentReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> interpretation High, low, normal, etc. */
		public array $interpretation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Comments about the observation */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept bodySite Observed body part */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $bodySite = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept method How it was done */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference specimen Specimen used for this observation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $specimen = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference device (Measurement) Device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $device = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Observation\ObservationReferenceRange> referenceRange Provides guide for interpretation */
		public array $referenceRange = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> hasMember Related resource that belongs to the Observation group */
		public array $hasMember = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> derivedFrom Related measurements the observation is made from */
		public array $derivedFrom = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Observation\ObservationComponent> component Component results */
		public array $component = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
