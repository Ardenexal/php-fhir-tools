<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Attachment
 *
 * @description For referring to data content defined in other formats.
 */
#[FHIRComplexType(typeName: 'Attachment', fhirVersion: 'R4')]
class Attachment extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var MimeTypesType|null contentType Mime type of the content, with charset etc. */
        public ?MimeTypesType $contentType = null,
        /** @var string|null language Human language of the content (BCP-47) */
        public ?string $language = null,
        /** @var Base64BinaryPrimitive|null data Data inline, base64ed */
        public ?Base64BinaryPrimitive $data = null,
        /** @var UrlPrimitive|null url Uri where the data can be found */
        public ?UrlPrimitive $url = null,
        /** @var UnsignedIntPrimitive|null size Number of bytes of content (if url provided) */
        public ?UnsignedIntPrimitive $size = null,
        /** @var Base64BinaryPrimitive|null hash Hash of the data (sha-1, base64ed) */
        public ?Base64BinaryPrimitive $hash = null,
        /** @var StringPrimitive|string|null title Label to display in place of the data */
        public StringPrimitive|string|null $title = null,
        /** @var DateTimePrimitive|null creation Date attachment was first created */
        public ?DateTimePrimitive $creation = null,
    ) {
        parent::__construct($id, $extension);
    }
}
