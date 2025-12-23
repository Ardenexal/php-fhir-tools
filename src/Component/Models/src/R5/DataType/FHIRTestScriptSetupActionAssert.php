<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element TestScript.setup.action.assert
 * @description Evaluates the results of previous operations to determine if the server under test behaves appropriately.
 */
class FHIRTestScriptSetupActionAssert extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string label Tracking/logging assertion label */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $label = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string description Tracking/reporting assertion description */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAssertionDirectionTypeType direction response | request */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAssertionDirectionTypeType $direction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string compareToSourceId Id of the source fixture to be evaluated */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $compareToSourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string compareToSourceExpression The FHIRPath expression to evaluate against the source fixture */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $compareToSourceExpression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string compareToSourcePath XPath or JSONPath expression to evaluate against the source fixture */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $compareToSourcePath = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMimeTypesType contentType Mime type to compare against the 'Content-Type' header */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMimeTypesType $contentType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAssertionManualCompletionTypeType defaultManualCompletion fail | pass | skip | stop */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAssertionManualCompletionTypeType $defaultManualCompletion = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string expression The FHIRPath expression to be evaluated */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $expression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string headerField HTTP header field name */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $headerField = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string minimumId Fixture Id of minimum content resource */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $minimumId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean navigationLinks Perform validation on navigation links? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $navigationLinks = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAssertionOperatorTypeType operator equals | notEquals | in | notIn | greaterThan | lessThan | empty | notEmpty | contains | notContains | eval | manualEval */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAssertionOperatorTypeType $operator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string path XPath or JSONPath expression */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTestScriptRequestMethodCodeType requestMethod delete | get | options | patch | post | put | head */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTestScriptRequestMethodCodeType $requestMethod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string requestURL Request URL comparison value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $requestURL = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri resource Resource type */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAssertionResponseTypesType response continue | switchingProtocols | okay | created | accepted | nonAuthoritativeInformation | noContent | resetContent | partialContent | multipleChoices | movedPermanently | found | seeOther | notModified | useProxy | temporaryRedirect | permanentRedirect | badRequest | unauthorized | paymentRequired | forbidden | notFound | methodNotAllowed | notAcceptable | proxyAuthenticationRequired | requestTimeout | conflict | gone | lengthRequired | preconditionFailed | contentTooLarge | uriTooLong | unsupportedMediaType | rangeNotSatisfiable | expectationFailed | misdirectedRequest | unprocessableContent | upgradeRequired | internalServerError | notImplemented | badGateway | serviceUnavailable | gatewayTimeout | httpVersionNotSupported */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAssertionResponseTypesType $response = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string responseCode HTTP response code to test */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $responseCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId sourceId Fixture Id of source expression or headerField */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $sourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean stopTestOnFail If this assert fails, will the current test execution stop? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $stopTestOnFail = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId validateProfileId Profile Id of validation profile reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $validateProfileId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string value The value to compare to */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean warningOnly Will this assert produce a warning only on error? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $warningOnly = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTestScriptSetupActionAssertRequirement> requirement Links or references to the testing requirements */
		public array $requirement = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
