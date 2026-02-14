<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Attachment
 * @description For referring to data content defined in other formats.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Attachment', fhirVersion: 'R4')]
class Attachment extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType contentType Mime type of the content, with charset etc. */
		public ?MimeTypesType $contentType = null,
		/** @var null|string language Human language of the content (BCP-47) */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive data Data inline, base64ed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive $data = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive url Uri where the data can be found */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive size Number of bytes of content (if url provided) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive $size = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive hash Hash of the data (sha-1, base64ed) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive $hash = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string title Label to display in place of the data */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive creation Date attachment was first created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $creation = null,
	) {
		parent::__construct($id, $extension);
	}
}
