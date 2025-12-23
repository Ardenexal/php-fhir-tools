<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/RelatedArtifact
 * @description Related artifacts such as additional documentation, justification, or bibliographic references.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'RelatedArtifact', fhirVersion: 'R5')]
class FHIRRelatedArtifact extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRelatedArtifactTypeType type documentation | justification | citation | predecessor | successor | derived-from | depends-on | composed-of | part-of | amends | amended-with | appends | appended-with | cites | cited-by | comments-on | comment-in | contains | contained-in | corrects | correction-in | replaces | replaced-with | retracts | retracted-by | signs | similar-to | supports | supported-with | transforms | transformed-into | transformed-with | documents | specification-of | created-with | cite-as */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRelatedArtifactTypeType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> classifier Additional classifiers */
		public array $classifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string label Short label */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $label = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string display Brief description of the related artifact */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $display = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown citation Bibliographic citation for the artifact */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $citation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment document What document is being referenced */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment $document = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical resource What artifact is being referenced */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference resourceReference What artifact, if not a conformance resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $resourceReference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPublicationStatusType publicationStatus draft | active | retired | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPublicationStatusType $publicationStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate publicationDate Date of publication of the artifact being referred to */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate $publicationDate = null,
	) {
		parent::__construct($id, $extension);
	}
}
