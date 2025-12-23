<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/DocumentReference
 * @description A reference to a document of any kind for any purpose. While the term “document” implies a more narrow focus, for this resource this "document" encompasses *any* serialized object with a mime-type, it includes formal patient-centric documents (CDA), clinical notes, scanned paper, non-patient specific documents like policy text, as well as a photo, video, or audio recording acquired or used in healthcare.  The DocumentReference resource provides metadata about the document so that the document can be discovered and managed.  The actual content may be inline base64 encoded data or provided by direct reference.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'DocumentReference',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/DocumentReference',
	fhirVersion: 'R5',
)]
class FHIRDocumentReference extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Business identifiers for the document */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string version An explicitly assigned identifer of a variation of the content in the DocumentReference */
		public FHIRString|string|null $version = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> basedOn Procedure that caused this media to be created */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDocumentReferenceStatusType status current | superseded | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDocumentReferenceStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCompositionStatusType docStatus registered | partial | preliminary | final | amended | corrected | appended | cancelled | entered-in-error | deprecated | unknown */
		public ?FHIRCompositionStatusType $docStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> modality Imaging modality used */
		public array $modality = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Kind of document (LOINC if possible) */
		public ?FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> category Categorization of document */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subject Who/what is the subject of the document */
		public ?FHIRReference $subject = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> context Context of the document content */
		public array $context = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> event Main clinical acts documented */
		public array $event = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> bodySite Body part included */
		public array $bodySite = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept facilityType Kind of facility where patient was seen */
		public ?FHIRCodeableConcept $facilityType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept practiceSetting Additional details about where the content was created (e.g. clinical specialty) */
		public ?FHIRCodeableConcept $practiceSetting = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod period Time of service that is being documented */
		public ?FHIRPeriod $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant date When this document reference was created */
		public ?FHIRInstant $date = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> author Who and/or what authored the document */
		public array $author = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDocumentReferenceAttester> attester Attests to accuracy of the document */
		public array $attester = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference custodian Organization which maintains the document */
		public ?FHIRReference $custodian = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDocumentReferenceRelatesTo> relatesTo Relationships to other documents */
		public array $relatesTo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Human-readable description */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> securityLabel Document security-tags */
		public array $securityLabel = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDocumentReferenceContent> content Document referenced */
		public array $content = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
