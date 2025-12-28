<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBase64Binary;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMimeTypesType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Attachment
 *
 * @description For referring to data content defined in other formats.
 */
#[FHIRComplexType(typeName: 'Attachment', fhirVersion: 'R5')]
class FHIRAttachment extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRMimeTypesType|null contentType Mime type of the content, with charset etc. */
        public ?FHIRMimeTypesType $contentType = null,
        /** @var FHIRAllLanguagesType|null language Human language of the content (BCP-47) */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRBase64Binary|null data Data inline, base64ed */
        public ?FHIRBase64Binary $data = null,
        /** @var FHIRUrl|null url Uri where the data can be found */
        public ?FHIRUrl $url = null,
        /** @var FHIRInteger64|null size Number of bytes of content (if url provided) */
        public ?FHIRInteger64 $size = null,
        /** @var FHIRBase64Binary|null hash Hash of the data (sha-1, base64ed) */
        public ?FHIRBase64Binary $hash = null,
        /** @var FHIRString|string|null title Label to display in place of the data */
        public FHIRString|string|null $title = null,
        /** @var FHIRDateTime|null creation Date attachment was first created */
        public ?FHIRDateTime $creation = null,
        /** @var FHIRPositiveInt|null height Height of the image in pixels (photo/video) */
        public ?FHIRPositiveInt $height = null,
        /** @var FHIRPositiveInt|null width Width of the image in pixels (photo/video) */
        public ?FHIRPositiveInt $width = null,
        /** @var FHIRPositiveInt|null frames Number of frames if > 1 (photo) */
        public ?FHIRPositiveInt $frames = null,
        /** @var FHIRDecimal|null duration Length in seconds (audio / video) */
        public ?FHIRDecimal $duration = null,
        /** @var FHIRPositiveInt|null pages Number of printed pages */
        public ?FHIRPositiveInt $pages = null,
    ) {
        parent::__construct($id, $extension);
    }
}
