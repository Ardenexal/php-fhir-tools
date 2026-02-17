<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDefinedTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TestScriptRequestMethodCodeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The operation to perform.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.setup.action.operation', fhirVersion: 'R4')]
class TestScriptSetupActionOperation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Coding|null type The operation code type that will be executed */
        public ?Coding $type = null,
        /** @var FHIRDefinedTypeType|null resource Resource type */
        public ?FHIRDefinedTypeType $resource = null,
        /** @var StringPrimitive|string|null label Tracking/logging operation label */
        public StringPrimitive|string|null $label = null,
        /** @var StringPrimitive|string|null description Tracking/reporting operation description */
        public StringPrimitive|string|null $description = null,
        /** @var MimeTypesType|null accept Mime type to accept in the payload of the response, with charset etc. */
        public ?MimeTypesType $accept = null,
        /** @var MimeTypesType|null contentType Mime type of the request payload contents, with charset etc. */
        public ?MimeTypesType $contentType = null,
        /** @var int|null destination Server responding to the request */
        public ?int $destination = null,
        /** @var bool|null encodeRequestUrl Whether or not to send the request url in encoded format */
        #[NotBlank]
        public ?bool $encodeRequestUrl = null,
        /** @var TestScriptRequestMethodCodeType|null method delete | get | options | patch | post | put | head */
        public ?TestScriptRequestMethodCodeType $method = null,
        /** @var int|null origin Server initiating the request */
        public ?int $origin = null,
        /** @var StringPrimitive|string|null params Explicitly defined path parameters */
        public StringPrimitive|string|null $params = null,
        /** @var array<TestScriptSetupActionOperationRequestHeader> requestHeader Each operation can have one or more header elements */
        public array $requestHeader = [],
        /** @var IdPrimitive|null requestId Fixture Id of mapped request */
        public ?IdPrimitive $requestId = null,
        /** @var IdPrimitive|null responseId Fixture Id of mapped response */
        public ?IdPrimitive $responseId = null,
        /** @var IdPrimitive|null sourceId Fixture Id of body for PUT and POST requests */
        public ?IdPrimitive $sourceId = null,
        /** @var IdPrimitive|null targetId Id of fixture used for extracting the [id],  [type], and [vid] for GET requests */
        public ?IdPrimitive $targetId = null,
        /** @var StringPrimitive|string|null url Request URL */
        public StringPrimitive|string|null $url = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
