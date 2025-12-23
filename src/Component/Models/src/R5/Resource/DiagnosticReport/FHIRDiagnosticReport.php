<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/DiagnosticReport
 * @description The findings and interpretation of diagnostic tests performed on patients, groups of patients, products, substances, devices, and locations, and/or specimens derived from these. The report includes clinical context such as requesting provider information, and some mix of atomic results, images, textual and coded interpretations, and formatted representation of diagnostic reports. The report also includes non-clinical context such as batch analysis and stability reporting of products and substances.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'DiagnosticReport',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport',
	fhirVersion: 'R5',
)]
class FHIRDiagnosticReport extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Business identifier for report */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> basedOn What was requested */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDiagnosticReportStatusType status registered | partial | preliminary | modified | final | amended | corrected | appended | cancelled | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDiagnosticReportStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> category Service category */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Name/Code for this diagnostic report */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subject The subject of the report - usually, but not always, the patient */
		public ?FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference encounter Health care event when test ordered */
		public ?FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod effectiveX Clinically relevant time/time-period for report */
		public FHIRDateTime|FHIRPeriod|null $effectiveX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant issued DateTime this version was made */
		public ?FHIRInstant $issued = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> performer Responsible Diagnostic Service */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> resultsInterpreter Primary result interpreter */
		public array $resultsInterpreter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> specimen Specimens this report is based on */
		public array $specimen = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> result Observations */
		public array $result = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Comments about the diagnostic report */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> study Reference to full details of an analysis associated with the diagnostic report */
		public array $study = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDiagnosticReportSupportingInfo> supportingInfo Additional information supporting the diagnostic report */
		public array $supportingInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDiagnosticReportMedia> media Key images or data associated with this report */
		public array $media = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference composition Reference to a Composition resource for the DiagnosticReport structure */
		public ?FHIRReference $composition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown conclusion Clinical conclusion (interpretation) of test results */
		public ?FHIRMarkdown $conclusion = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> conclusionCode Codes for the clinical conclusion of test results */
		public array $conclusionCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment> presentedForm Entire report as issued */
		public array $presentedForm = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
