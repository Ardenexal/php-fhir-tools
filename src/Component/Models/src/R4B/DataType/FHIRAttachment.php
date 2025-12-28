<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBase64Binary;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUnsignedInt;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUrl;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMimeTypesType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Attachment
 *
 * @description For referring to data content defined in other formats.
 */
#[FHIRComplexType(typeName: 'Attachment', fhirVersion: 'R4B')]
class FHIRAttachment extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRMimeTypesType|null contentType Mime type of the content, with charset etc. */
        public ?FHIRMimeTypesType $contentType = null,
        /** @var string|null language Human language of the content (BCP-47) */
        public ?string $language = null,
        /** @var FHIRBase64Binary|null data Data inline, base64ed */
        public ?FHIRBase64Binary $data = null,
        /** @var FHIRUrl|null url Uri where the data can be found */
        public ?FHIRUrl $url = null,
        /** @var FHIRUnsignedInt|null size Number of bytes of content (if url provided) */
        public ?FHIRUnsignedInt $size = null,
        /** @var FHIRBase64Binary|null hash Hash of the data (sha-1, base64ed) */
        public ?FHIRBase64Binary $hash = null,
        /** @var FHIRString|string|null title Label to display in place of the data */
        public FHIRString|string|null $title = null,
        /** @var FHIRDateTime|null creation Date attachment was first created */
        public ?FHIRDateTime $creation = null,
    ) {
        parent::__construct($id, $extension);
    }
}
