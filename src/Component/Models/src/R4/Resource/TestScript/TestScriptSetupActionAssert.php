<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

/**
 * @description Evaluates the results of previous operations to determine if the server under test behaves appropriately.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.setup.action.assert', fhirVersion: 'R4')]
class TestScriptSetupActionAssert extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string label Tracking/logging assertion label */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $label = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Tracking/reporting assertion description */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AssertionDirectionTypeType direction response | request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AssertionDirectionTypeType $direction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string compareToSourceId Id of the source fixture to be evaluated */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $compareToSourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string compareToSourceExpression The FHIRPath expression to evaluate against the source fixture */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $compareToSourceExpression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string compareToSourcePath XPath or JSONPath expression to evaluate against the source fixture */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $compareToSourcePath = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType contentType Mime type to compare against the 'Content-Type' header */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType $contentType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string expression The FHIRPath expression to be evaluated */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $expression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string headerField HTTP header field name */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $headerField = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string minimumId Fixture Id of minimum content resource */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $minimumId = null,
		/** @var null|bool navigationLinks Perform validation on navigation links? */
		public ?bool $navigationLinks = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AssertionOperatorTypeType operator equals | notEquals | in | notIn | greaterThan | lessThan | empty | notEmpty | contains | notContains | eval */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AssertionOperatorTypeType $operator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string path XPath or JSONPath expression */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\TestScriptRequestMethodCodeType requestMethod delete | get | options | patch | post | put | head */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\TestScriptRequestMethodCodeType $requestMethod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string requestURL Request URL comparison value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $requestURL = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDefinedTypeType resource Resource type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDefinedTypeType $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AssertionResponseTypesType response okay | created | noContent | notModified | bad | forbidden | notFound | methodNotAllowed | conflict | gone | preconditionFailed | unprocessable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AssertionResponseTypesType $response = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string responseCode HTTP response code to test */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $responseCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive sourceId Fixture Id of source expression or headerField */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $sourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive validateProfileId Profile Id of validation profile reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $validateProfileId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string value The value to compare to */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $value = null,
		/** @var null|bool warningOnly Will this assert produce a warning only on error? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?bool $warningOnly = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
