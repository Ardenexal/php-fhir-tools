<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
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
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.String',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'contentType' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'language' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'data' => [
            'fhirType'     => 'base64Binary',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'url' => [
            'fhirType'     => 'url',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'size' => [
            'fhirType'     => 'unsignedInt',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'hash' => [
            'fhirType'     => 'base64Binary',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'title' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'creation' => [
            'fhirType'     => 'dateTime',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var MimeTypesType|null contentType Mime type of the content, with charset etc. */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?MimeTypesType $contentType = null,
        /** @var string|null language Human language of the content (BCP-47) */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?string $language = null,
        /** @var Base64BinaryPrimitive|null data Data inline, base64ed */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive')]
        public ?Base64BinaryPrimitive $data = null,
        /** @var UrlPrimitive|null url Uri where the data can be found */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive')]
        public ?UrlPrimitive $url = null,
        /** @var UnsignedIntPrimitive|null size Number of bytes of content (if url provided) */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $size = null,
        /** @var Base64BinaryPrimitive|null hash Hash of the data (sha-1, base64ed) */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive')]
        public ?Base64BinaryPrimitive $hash = null,
        /** @var StringPrimitive|string|null title Label to display in place of the data */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $title = null,
        /** @var DateTimePrimitive|null creation Date attachment was first created */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $creation = null,
    ) {
        parent::__construct($id, $extension);
    }
}
