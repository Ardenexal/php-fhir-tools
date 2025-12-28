<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/RelatedArtifact
 * @description Related artifacts such as additional documentation, justification, or bibliographic references.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'RelatedArtifact', fhirVersion: 'R5')]
class FHIRRelatedArtifact extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifactTypeType type documentation | justification | citation | predecessor | successor | derived-from | depends-on | composed-of | part-of | amends | amended-with | appends | appended-with | cites | cited-by | comments-on | comment-in | contains | contained-in | corrects | correction-in | replaces | replaced-with | retracts | retracted-by | signs | similar-to | supports | supported-with | transforms | transformed-into | transformed-with | documents | specification-of | created-with | cite-as */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRRelatedArtifactTypeType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> classifier Additional classifiers */
		public array $classifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string label Short label */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $label = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string display Brief description of the related artifact */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $display = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown citation Bibliographic citation for the artifact */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $citation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment document What document is being referenced */
		public ?FHIRAttachment $document = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical resource What artifact is being referenced */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference resourceReference What artifact, if not a conformance resource */
		public ?FHIRReference $resourceReference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPublicationStatusType publicationStatus draft | active | retired | unknown */
		public ?FHIRPublicationStatusType $publicationStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate publicationDate Date of publication of the artifact being referred to */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate $publicationDate = null,
	) {
		parent::__construct($id, $extension);
	}
}
