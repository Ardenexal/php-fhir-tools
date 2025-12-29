<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Evaluates the results of previous operations to determine if the server under test behaves appropriately.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.setup.action.assert', fhirVersion: 'R4B')]
class FHIRTestScriptSetupActionAssert extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string label Tracking/logging assertion label */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $label = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string description Tracking/reporting assertion description */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAssertionDirectionTypeType direction response | request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAssertionDirectionTypeType $direction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string compareToSourceId Id of the source fixture to be evaluated */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $compareToSourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string compareToSourceExpression The FHIRPath expression to evaluate against the source fixture */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $compareToSourceExpression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string compareToSourcePath XPath or JSONPath expression to evaluate against the source fixture */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $compareToSourcePath = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMimeTypesType contentType Mime type to compare against the 'Content-Type' header */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMimeTypesType $contentType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string expression The FHIRPath expression to be evaluated */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $expression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string headerField HTTP header field name */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $headerField = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string minimumId Fixture Id of minimum content resource */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $minimumId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean navigationLinks Perform validation on navigation links? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $navigationLinks = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAssertionOperatorTypeType operator equals | notEquals | in | notIn | greaterThan | lessThan | empty | notEmpty | contains | notContains | eval */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAssertionOperatorTypeType $operator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string path XPath or JSONPath expression */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTestScriptRequestMethodCodeType requestMethod delete | get | options | patch | post | put | head */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTestScriptRequestMethodCodeType $requestMethod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string requestURL Request URL comparison value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $requestURL = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRFHIRDefinedTypeType resource Resource type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRFHIRDefinedTypeType $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAssertionResponseTypesType response okay | created | noContent | notModified | bad | forbidden | notFound | methodNotAllowed | conflict | gone | preconditionFailed | unprocessable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAssertionResponseTypesType $response = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string responseCode HTTP response code to test */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $responseCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId sourceId Fixture Id of source expression or headerField */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId $sourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId validateProfileId Profile Id of validation profile reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId $validateProfileId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string value The value to compare to */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean warningOnly Will this assert produce a warning only on error? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $warningOnly = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
