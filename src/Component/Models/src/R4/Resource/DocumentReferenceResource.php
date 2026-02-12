<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Structured Documents)
 * @see http://hl7.org/fhir/StructureDefinition/DocumentReference
 * @description A reference to a document of any kind for any purpose. Provides metadata about the document so that the document can be discovered and managed. The scope of a document is any seralized object with a mime-type, so includes formal patient centric documents (CDA), cliical notes, scanned paper, and non-patient specific documents like policy text.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'DocumentReference',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/DocumentReference',
	fhirVersion: 'R4',
)]
class DocumentReferenceResource extends DomainResourceResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier masterIdentifier Master Version Specific Identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $masterIdentifier = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Other identifiers for the document */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\DocumentReferenceStatusType status current | superseded | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\DocumentReferenceStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CompositionStatusType docStatus preliminary | final | amended | entered-in-error */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CompositionStatusType $docStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Kind of document (LOINC if possible) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> category Categorization of document */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject Who/what is the subject of the document */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive date When this document reference was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive $date = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> author Who and/or what authored the document */
		public array $author = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference authenticator Who/what authenticated the document */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $authenticator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference custodian Organization which maintains the document */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $custodian = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentReference\DocumentReferenceRelatesTo> relatesTo Relationships to other documents */
		public array $relatesTo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Human-readable description */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> securityLabel Document security-tags */
		public array $securityLabel = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentReference\DocumentReferenceContent> content Document referenced */
		public array $content = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentReference\DocumentReferenceContext context Clinical context of document */
		public ?DocumentReference\DocumentReferenceContext $context = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
