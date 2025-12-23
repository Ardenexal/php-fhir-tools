<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 * @see http://hl7.org/fhir/StructureDefinition/EvidenceReport
 * @description The EvidenceReport Resource is a specialized container for a collection of resources and codable concepts, adapted to support compositions of Evidence, EvidenceVariable, and Citation resources and related concepts.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'EvidenceReport',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/EvidenceReport',
	fhirVersion: 'R4B',
)]
class FHIREvidenceReport extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri url Canonical identifier for this EvidenceReport, represented as a globally unique URI */
		public ?FHIRUri $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPublicationStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Unique identifier for the evidence report */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> relatedIdentifier Identifiers for articles that may relate to more than one evidence report */
		public array $relatedIdentifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown citeAsX Citation for this report */
		public FHIRReference|FHIRMarkdown|null $citeAsX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type Kind of report */
		public ?FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAnnotation> note Used for footnotes and annotations */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRelatedArtifact> relatedArtifact Link, description or reference to artifact associated with the report */
		public array $relatedArtifact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREvidenceReportSubject subject Focus of the report */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIREvidenceReportSubject $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string publisher Name of the publisher (organization or individual) */
		public FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> author Who authored the content */
		public array $author = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> editor Who edited the content */
		public array $editor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> reviewer Who reviewed the content */
		public array $reviewer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> endorser Who endorsed the content */
		public array $endorser = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREvidenceReportRelatesTo> relatesTo Relationships to other compositions/documents */
		public array $relatesTo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREvidenceReportSection> section Composition is broken into sections */
		public array $section = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
