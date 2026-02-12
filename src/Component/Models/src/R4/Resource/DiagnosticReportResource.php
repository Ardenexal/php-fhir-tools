<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/DiagnosticReport
 * @description The findings and interpretation of diagnostic  tests performed on patients, groups of patients, devices, and locations, and/or specimens derived from these. The report includes clinical context such as requesting and provider information, and some mix of atomic results, images, textual and coded interpretations, and formatted representation of diagnostic reports.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'DiagnosticReport',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport',
	fhirVersion: 'R4',
)]
class DiagnosticReportResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business identifier for report */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> basedOn What was requested */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\DiagnosticReportStatusType status registered | partial | preliminary | final + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\DiagnosticReportStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> category Service category */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Name/Code for this diagnostic report */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject The subject of the report - usually, but not always, the patient */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference encounter Health care event when test ordered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period effectiveX Clinically relevant time/time-period for report */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|null $effectiveX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive issued DateTime this version was made */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive $issued = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> performer Responsible Diagnostic Service */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> resultsInterpreter Primary result interpreter */
		public array $resultsInterpreter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> specimen Specimens this report is based on */
		public array $specimen = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> result Observations */
		public array $result = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> imagingStudy Reference to full details of imaging associated with the diagnostic report */
		public array $imagingStudy = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\DiagnosticReport\DiagnosticReportMedia> media Key images associated with this report */
		public array $media = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string conclusion Clinical conclusion (interpretation) of test results */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $conclusion = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> conclusionCode Codes for the clinical conclusion of test results */
		public array $conclusionCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment> presentedForm Entire report as issued */
		public array $presentedForm = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
