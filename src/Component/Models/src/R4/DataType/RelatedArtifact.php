<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/RelatedArtifact
 * @description Related artifacts such as additional documentation, justification, or bibliographic references.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'RelatedArtifact', fhirVersion: 'R4')]
class RelatedArtifact extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifactTypeType type documentation | justification | citation | predecessor | successor | derived-from | depends-on | composed-of */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?RelatedArtifactTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string label Short label */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $label = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string display Brief description of the related artifact */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $display = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive citation Bibliographic citation for the artifact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $citation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive url Where the artifact can be accessed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment document What document is being referenced */
		public ?Attachment $document = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive resource What resource is being referenced */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $resource = null,
	) {
		parent::__construct($id, $extension);
	}
}
