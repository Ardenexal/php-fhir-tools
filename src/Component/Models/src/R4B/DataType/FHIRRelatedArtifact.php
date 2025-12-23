<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/RelatedArtifact
 * @description Related artifacts such as additional documentation, justification, or bibliographic references.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'RelatedArtifact', fhirVersion: 'R4B')]
class FHIRRelatedArtifact extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRelatedArtifactTypeType type documentation | justification | citation | predecessor | successor | derived-from | depends-on | composed-of */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRelatedArtifactTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string label Short label */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $label = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string display Brief description of the related artifact */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $display = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown citation Bibliographic citation for the artifact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown $citation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUrl url Where the artifact can be accessed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUrl $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAttachment document What document is being referenced */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAttachment $document = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical resource What resource is being referenced */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical $resource = null,
	) {
		parent::__construct($id, $extension);
	}
}
