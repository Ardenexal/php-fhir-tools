<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\Integer64Primitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Attachment
 *
 * @description For referring to data content defined in other formats.
 */
#[FHIRComplexType(typeName: 'Attachment', fhirVersion: 'R5')]
class Attachment extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var MimeTypesType|null contentType Mime type of the content, with charset etc. */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?MimeTypesType $contentType = null,
        /** @var AllLanguagesType|null language Human language of the content (BCP-47) */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Base64BinaryPrimitive|null data Data inline, base64ed */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive')]
        public ?Base64BinaryPrimitive $data = null,
        /** @var UrlPrimitive|null url Uri where the data can be found */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive')]
        public ?UrlPrimitive $url = null,
        /** @var Integer64Primitive|null size Number of bytes of content (if url provided) */
        #[FhirProperty(fhirType: 'integer64', propertyKind: 'primitive')]
        public ?Integer64Primitive $size = null,
        /** @var Base64BinaryPrimitive|null hash Hash of the data (sha-1, base64ed) */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive')]
        public ?Base64BinaryPrimitive $hash = null,
        /** @var StringPrimitive|string|null title Label to display in place of the data */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $title = null,
        /** @var DateTimePrimitive|null creation Date attachment was first created */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $creation = null,
        /** @var PositiveIntPrimitive|null height Height of the image in pixels (photo/video) */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $height = null,
        /** @var PositiveIntPrimitive|null width Width of the image in pixels (photo/video) */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $width = null,
        /** @var PositiveIntPrimitive|null frames Number of frames if > 1 (photo) */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $frames = null,
        /** @var numeric-string|null duration Length in seconds (audio / video) */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $duration = null,
        /** @var PositiveIntPrimitive|null pages Number of printed pages */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $pages = null,
    ) {
        parent::__construct($id, $extension);
    }
}
