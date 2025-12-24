<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The operation to perform.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.setup.action.operation', fhirVersion: 'R5')]
class FHIRTestScriptSetupActionOperation extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCoding|null type The operation code type that will be executed */
        public ?FHIRCoding $type = null,
        /** @var FHIRUri|null resource Resource type */
        public ?FHIRUri $resource = null,
        /** @var FHIRString|string|null label Tracking/logging operation label */
        public FHIRString|string|null $label = null,
        /** @var FHIRString|string|null description Tracking/reporting operation description */
        public FHIRString|string|null $description = null,
        /** @var FHIRMimeTypesType|null accept Mime type to accept in the payload of the response, with charset etc */
        public ?FHIRMimeTypesType $accept = null,
        /** @var FHIRMimeTypesType|null contentType Mime type of the request payload contents, with charset etc */
        public ?FHIRMimeTypesType $contentType = null,
        /** @var FHIRInteger|null destination Server responding to the request */
        public ?FHIRInteger $destination = null,
        /** @var FHIRBoolean|null encodeRequestUrl Whether or not to send the request url in encoded format */
        #[NotBlank]
        public ?FHIRBoolean $encodeRequestUrl = null,
        /** @var FHIRTestScriptRequestMethodCodeType|null method delete | get | options | patch | post | put | head */
        public ?FHIRTestScriptRequestMethodCodeType $method = null,
        /** @var FHIRInteger|null origin Server initiating the request */
        public ?FHIRInteger $origin = null,
        /** @var FHIRString|string|null params Explicitly defined path parameters */
        public FHIRString|string|null $params = null,
        /** @var array<FHIRTestScriptSetupActionOperationRequestHeader> requestHeader Each operation can have one or more header elements */
        public array $requestHeader = [],
        /** @var FHIRId|null requestId Fixture Id of mapped request */
        public ?FHIRId $requestId = null,
        /** @var FHIRId|null responseId Fixture Id of mapped response */
        public ?FHIRId $responseId = null,
        /** @var FHIRId|null sourceId Fixture Id of body for PUT and POST requests */
        public ?FHIRId $sourceId = null,
        /** @var FHIRId|null targetId Id of fixture used for extracting the [id],  [type], and [vid] for GET requests */
        public ?FHIRId $targetId = null,
        /** @var FHIRString|string|null url Request URL */
        public FHIRString|string|null $url = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
