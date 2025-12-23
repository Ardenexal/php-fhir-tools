<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element TestScript.setup.action.operation
 * @description The operation to perform.
 */
class FHIRTestScriptSetupActionOperation extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding type The operation code type that will be executed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRFHIRDefinedTypeType resource Resource type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRFHIRDefinedTypeType $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string label Tracking/logging operation label */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $label = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string description Tracking/reporting operation description */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMimeTypesType accept Mime type to accept in the payload of the response, with charset etc. */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMimeTypesType $accept = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMimeTypesType contentType Mime type of the request payload contents, with charset etc. */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMimeTypesType $contentType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger destination Server responding to the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $destination = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean encodeRequestUrl Whether or not to send the request url in encoded format */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $encodeRequestUrl = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTestScriptRequestMethodCodeType method delete | get | options | patch | post | put | head */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTestScriptRequestMethodCodeType $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger origin Server initiating the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $origin = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string params Explicitly defined path parameters */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $params = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTestScriptSetupActionOperationRequestHeader> requestHeader Each operation can have one or more header elements */
		public array $requestHeader = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId requestId Fixture Id of mapped request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId $requestId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId responseId Fixture Id of mapped response */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId $responseId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId sourceId Fixture Id of body for PUT and POST requests */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId $sourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId targetId Id of fixture used for extracting the [id],  [type], and [vid] for GET requests */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId $targetId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string url Request URL */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $url = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
