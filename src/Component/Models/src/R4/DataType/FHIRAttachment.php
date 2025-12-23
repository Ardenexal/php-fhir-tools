<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Attachment
 * @description For referring to data content defined in other formats.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Attachment', fhirVersion: 'R4')]
class FHIRAttachment extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMimeTypesType contentType Mime type of the content, with charset etc. */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMimeTypesType $contentType = null,
		/** @var null|string language Human language of the content (BCP-47) */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBase64Binary data Data inline, base64ed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBase64Binary $data = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUrl url Uri where the data can be found */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUrl $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt size Number of bytes of content (if url provided) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt $size = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBase64Binary hash Hash of the data (sha-1, base64ed) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBase64Binary $hash = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string title Label to display in place of the data */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime creation Date attachment was first created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime $creation = null,
	) {
		parent::__construct($id, $extension);
	}
}
