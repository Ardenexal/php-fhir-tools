<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

/**
 * @description The operation to perform.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.setup.action.operation', fhirVersion: 'R4')]
class TestScriptSetupActionOperation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding type The operation code type that will be executed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDefinedTypeType resource Resource type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDefinedTypeType $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string label Tracking/logging operation label */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $label = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Tracking/reporting operation description */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType accept Mime type to accept in the payload of the response, with charset etc. */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType $accept = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType contentType Mime type of the request payload contents, with charset etc. */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType $contentType = null,
		/** @var null|int destination Server responding to the request */
		public ?int $destination = null,
		/** @var null|bool encodeRequestUrl Whether or not to send the request url in encoded format */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?bool $encodeRequestUrl = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\TestScriptRequestMethodCodeType method delete | get | options | patch | post | put | head */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\TestScriptRequestMethodCodeType $method = null,
		/** @var null|int origin Server initiating the request */
		public ?int $origin = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string params Explicitly defined path parameters */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $params = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptSetupActionOperationRequestHeader> requestHeader Each operation can have one or more header elements */
		public array $requestHeader = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive requestId Fixture Id of mapped request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $requestId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive responseId Fixture Id of mapped response */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $responseId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive sourceId Fixture Id of body for PUT and POST requests */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $sourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive targetId Id of fixture used for extracting the [id],  [type], and [vid] for GET requests */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $targetId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string url Request URL */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $url = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
