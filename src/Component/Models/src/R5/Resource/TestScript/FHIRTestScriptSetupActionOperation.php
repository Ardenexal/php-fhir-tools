<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The operation to perform.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.setup.action.operation', fhirVersion: 'R5')]
class FHIRTestScriptSetupActionOperation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding type The operation code type that will be executed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri resource Resource type */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string label Tracking/logging operation label */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $label = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string description Tracking/reporting operation description */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMimeTypesType accept Mime type to accept in the payload of the response, with charset etc */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMimeTypesType $accept = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMimeTypesType contentType Mime type of the request payload contents, with charset etc */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMimeTypesType $contentType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger destination Server responding to the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $destination = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean encodeRequestUrl Whether or not to send the request url in encoded format */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $encodeRequestUrl = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTestScriptRequestMethodCodeType method delete | get | options | patch | post | put | head */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTestScriptRequestMethodCodeType $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger origin Server initiating the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $origin = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string params Explicitly defined path parameters */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $params = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTestScriptSetupActionOperationRequestHeader> requestHeader Each operation can have one or more header elements */
		public array $requestHeader = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId requestId Fixture Id of mapped request */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $requestId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId responseId Fixture Id of mapped response */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $responseId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId sourceId Fixture Id of body for PUT and POST requests */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $sourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId targetId Id of fixture used for extracting the [id],  [type], and [vid] for GET requests */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $targetId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string url Request URL */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $url = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
