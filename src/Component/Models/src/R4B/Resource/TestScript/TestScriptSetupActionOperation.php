<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\TestScript;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDefinedTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\MimeTypesType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\TestScriptRequestMethodCodeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The operation to perform.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.setup.action.operation', fhirVersion: 'R4B')]
class TestScriptSetupActionOperation extends BackboneElement
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
        'modifierExtension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'modifierExtension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'type' => [
            'fhirType'     => 'Coding',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'resource' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'label' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'description' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'accept' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
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
        'destination' => [
            'fhirType'     => 'integer',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'encodeRequestUrl' => [
            'fhirType'     => 'boolean',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'method' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'origin' => [
            'fhirType'     => 'integer',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'params' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'requestHeader' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'requestId' => [
            'fhirType'     => 'id',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'responseId' => [
            'fhirType'     => 'id',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'sourceId' => [
            'fhirType'     => 'id',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'targetId' => [
            'fhirType'     => 'id',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'url' => [
            'fhirType'     => 'string',
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
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var Coding|null type The operation code type that will be executed */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public ?Coding $type = null,
        /** @var FHIRDefinedTypeType|null resource Resource type */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?FHIRDefinedTypeType $resource = null,
        /** @var StringPrimitive|string|null label Tracking/logging operation label */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $label = null,
        /** @var StringPrimitive|string|null description Tracking/reporting operation description */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var MimeTypesType|null accept Mime type to accept in the payload of the response, with charset etc. */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?MimeTypesType $accept = null,
        /** @var MimeTypesType|null contentType Mime type of the request payload contents, with charset etc. */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?MimeTypesType $contentType = null,
        /** @var int|null destination Server responding to the request */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $destination = null,
        /** @var bool|null encodeRequestUrl Whether or not to send the request url in encoded format */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?bool $encodeRequestUrl = null,
        /** @var TestScriptRequestMethodCodeType|null method delete | get | options | patch | post | put | head */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?TestScriptRequestMethodCodeType $method = null,
        /** @var int|null origin Server initiating the request */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $origin = null,
        /** @var StringPrimitive|string|null params Explicitly defined path parameters */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $params = null,
        /** @var array<TestScriptSetupActionOperationRequestHeader> requestHeader Each operation can have one or more header elements */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
        public array $requestHeader = [],
        /** @var IdPrimitive|null requestId Fixture Id of mapped request */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $requestId = null,
        /** @var IdPrimitive|null responseId Fixture Id of mapped response */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $responseId = null,
        /** @var IdPrimitive|null sourceId Fixture Id of body for PUT and POST requests */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $sourceId = null,
        /** @var IdPrimitive|null targetId Id of fixture used for extracting the [id],  [type], and [vid] for GET requests */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $targetId = null,
        /** @var StringPrimitive|string|null url Request URL */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $url = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
