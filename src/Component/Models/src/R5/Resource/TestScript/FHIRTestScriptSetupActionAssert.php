<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAssertionDirectionTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAssertionManualCompletionTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAssertionOperatorTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAssertionResponseTypesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMimeTypesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTestScriptRequestMethodCodeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Evaluates the results of previous operations to determine if the server under test behaves appropriately.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.setup.action.assert', fhirVersion: 'R5')]
class FHIRTestScriptSetupActionAssert extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null label Tracking/logging assertion label */
        public FHIRString|string|null $label = null,
        /** @var FHIRString|string|null description Tracking/reporting assertion description */
        public FHIRString|string|null $description = null,
        /** @var FHIRAssertionDirectionTypeType|null direction response | request */
        public ?FHIRAssertionDirectionTypeType $direction = null,
        /** @var FHIRString|string|null compareToSourceId Id of the source fixture to be evaluated */
        public FHIRString|string|null $compareToSourceId = null,
        /** @var FHIRString|string|null compareToSourceExpression The FHIRPath expression to evaluate against the source fixture */
        public FHIRString|string|null $compareToSourceExpression = null,
        /** @var FHIRString|string|null compareToSourcePath XPath or JSONPath expression to evaluate against the source fixture */
        public FHIRString|string|null $compareToSourcePath = null,
        /** @var FHIRMimeTypesType|null contentType Mime type to compare against the 'Content-Type' header */
        public ?FHIRMimeTypesType $contentType = null,
        /** @var FHIRAssertionManualCompletionTypeType|null defaultManualCompletion fail | pass | skip | stop */
        public ?FHIRAssertionManualCompletionTypeType $defaultManualCompletion = null,
        /** @var FHIRString|string|null expression The FHIRPath expression to be evaluated */
        public FHIRString|string|null $expression = null,
        /** @var FHIRString|string|null headerField HTTP header field name */
        public FHIRString|string|null $headerField = null,
        /** @var FHIRString|string|null minimumId Fixture Id of minimum content resource */
        public FHIRString|string|null $minimumId = null,
        /** @var FHIRBoolean|null navigationLinks Perform validation on navigation links? */
        public ?FHIRBoolean $navigationLinks = null,
        /** @var FHIRAssertionOperatorTypeType|null operator equals | notEquals | in | notIn | greaterThan | lessThan | empty | notEmpty | contains | notContains | eval | manualEval */
        public ?FHIRAssertionOperatorTypeType $operator = null,
        /** @var FHIRString|string|null path XPath or JSONPath expression */
        public FHIRString|string|null $path = null,
        /** @var FHIRTestScriptRequestMethodCodeType|null requestMethod delete | get | options | patch | post | put | head */
        public ?FHIRTestScriptRequestMethodCodeType $requestMethod = null,
        /** @var FHIRString|string|null requestURL Request URL comparison value */
        public FHIRString|string|null $requestURL = null,
        /** @var FHIRUri|null resource Resource type */
        public ?FHIRUri $resource = null,
        /** @var FHIRAssertionResponseTypesType|null response continue | switchingProtocols | okay | created | accepted | nonAuthoritativeInformation | noContent | resetContent | partialContent | multipleChoices | movedPermanently | found | seeOther | notModified | useProxy | temporaryRedirect | permanentRedirect | badRequest | unauthorized | paymentRequired | forbidden | notFound | methodNotAllowed | notAcceptable | proxyAuthenticationRequired | requestTimeout | conflict | gone | lengthRequired | preconditionFailed | contentTooLarge | uriTooLong | unsupportedMediaType | rangeNotSatisfiable | expectationFailed | misdirectedRequest | unprocessableContent | upgradeRequired | internalServerError | notImplemented | badGateway | serviceUnavailable | gatewayTimeout | httpVersionNotSupported */
        public ?FHIRAssertionResponseTypesType $response = null,
        /** @var FHIRString|string|null responseCode HTTP response code to test */
        public FHIRString|string|null $responseCode = null,
        /** @var FHIRId|null sourceId Fixture Id of source expression or headerField */
        public ?FHIRId $sourceId = null,
        /** @var FHIRBoolean|null stopTestOnFail If this assert fails, will the current test execution stop? */
        #[NotBlank]
        public ?FHIRBoolean $stopTestOnFail = null,
        /** @var FHIRId|null validateProfileId Profile Id of validation profile reference */
        public ?FHIRId $validateProfileId = null,
        /** @var FHIRString|string|null value The value to compare to */
        public FHIRString|string|null $value = null,
        /** @var FHIRBoolean|null warningOnly Will this assert produce a warning only on error? */
        #[NotBlank]
        public ?FHIRBoolean $warningOnly = null,
        /** @var array<FHIRTestScriptSetupActionAssertRequirement> requirement Links or references to the testing requirements */
        public array $requirement = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
