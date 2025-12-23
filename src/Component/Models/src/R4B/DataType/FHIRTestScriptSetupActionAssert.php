<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element TestScript.setup.action.assert
 * @description Evaluates the results of previous operations to determine if the server under test behaves appropriately.
 */
class FHIRTestScriptSetupActionAssert extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string label Tracking/logging assertion label */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $label = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string description Tracking/reporting assertion description */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAssertionDirectionTypeType direction response | request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAssertionDirectionTypeType $direction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string compareToSourceId Id of the source fixture to be evaluated */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $compareToSourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string compareToSourceExpression The FHIRPath expression to evaluate against the source fixture */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $compareToSourceExpression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string compareToSourcePath XPath or JSONPath expression to evaluate against the source fixture */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $compareToSourcePath = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMimeTypesType contentType Mime type to compare against the 'Content-Type' header */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMimeTypesType $contentType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string expression The FHIRPath expression to be evaluated */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $expression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string headerField HTTP header field name */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $headerField = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string minimumId Fixture Id of minimum content resource */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $minimumId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean navigationLinks Perform validation on navigation links? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $navigationLinks = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAssertionOperatorTypeType operator equals | notEquals | in | notIn | greaterThan | lessThan | empty | notEmpty | contains | notContains | eval */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAssertionOperatorTypeType $operator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string path XPath or JSONPath expression */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTestScriptRequestMethodCodeType requestMethod delete | get | options | patch | post | put | head */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTestScriptRequestMethodCodeType $requestMethod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string requestURL Request URL comparison value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $requestURL = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRFHIRDefinedTypeType resource Resource type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRFHIRDefinedTypeType $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAssertionResponseTypesType response okay | created | noContent | notModified | bad | forbidden | notFound | methodNotAllowed | conflict | gone | preconditionFailed | unprocessable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAssertionResponseTypesType $response = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string responseCode HTTP response code to test */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $responseCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRId sourceId Fixture Id of source expression or headerField */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRId $sourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRId validateProfileId Profile Id of validation profile reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRId $validateProfileId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string value The value to compare to */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean warningOnly Will this assert produce a warning only on error? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $warningOnly = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
