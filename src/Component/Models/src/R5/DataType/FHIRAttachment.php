<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Attachment
 * @description For referring to data content defined in other formats.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Attachment', fhirVersion: 'R5')]
class FHIRAttachment extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMimeTypesType contentType Mime type of the content, with charset etc. */
		public ?FHIRMimeTypesType $contentType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Human language of the content (BCP-47) */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBase64Binary data Data inline, base64ed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBase64Binary $data = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl url Uri where the data can be found */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger64 size Number of bytes of content (if url provided) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger64 $size = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBase64Binary hash Hash of the data (sha-1, base64ed) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBase64Binary $hash = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string title Label to display in place of the data */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime creation Date attachment was first created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $creation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt height Height of the image in pixels (photo/video) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $height = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt width Width of the image in pixels (photo/video) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $width = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt frames Number of frames if > 1 (photo) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $frames = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal duration Length in seconds (audio / video) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $duration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt pages Number of printed pages */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $pages = null,
	) {
		parent::__construct($id, $extension);
	}
}
