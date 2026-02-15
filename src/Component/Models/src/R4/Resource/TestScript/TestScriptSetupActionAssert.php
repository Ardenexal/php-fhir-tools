<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AssertionDirectionTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AssertionOperatorTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AssertionResponseTypesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDefinedTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TestScriptRequestMethodCodeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Evaluates the results of previous operations to determine if the server under test behaves appropriately.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.setup.action.assert', fhirVersion: 'R4')]
class TestScriptSetupActionAssert extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null label Tracking/logging assertion label */
        public StringPrimitive|string|null $label = null,
        /** @var StringPrimitive|string|null description Tracking/reporting assertion description */
        public StringPrimitive|string|null $description = null,
        /** @var AssertionDirectionTypeType|null direction response | request */
        public ?AssertionDirectionTypeType $direction = null,
        /** @var StringPrimitive|string|null compareToSourceId Id of the source fixture to be evaluated */
        public StringPrimitive|string|null $compareToSourceId = null,
        /** @var StringPrimitive|string|null compareToSourceExpression The FHIRPath expression to evaluate against the source fixture */
        public StringPrimitive|string|null $compareToSourceExpression = null,
        /** @var StringPrimitive|string|null compareToSourcePath XPath or JSONPath expression to evaluate against the source fixture */
        public StringPrimitive|string|null $compareToSourcePath = null,
        /** @var MimeTypesType|null contentType Mime type to compare against the 'Content-Type' header */
        public ?MimeTypesType $contentType = null,
        /** @var StringPrimitive|string|null expression The FHIRPath expression to be evaluated */
        public StringPrimitive|string|null $expression = null,
        /** @var StringPrimitive|string|null headerField HTTP header field name */
        public StringPrimitive|string|null $headerField = null,
        /** @var StringPrimitive|string|null minimumId Fixture Id of minimum content resource */
        public StringPrimitive|string|null $minimumId = null,
        /** @var bool|null navigationLinks Perform validation on navigation links? */
        public ?bool $navigationLinks = null,
        /** @var AssertionOperatorTypeType|null operator equals | notEquals | in | notIn | greaterThan | lessThan | empty | notEmpty | contains | notContains | eval */
        public ?AssertionOperatorTypeType $operator = null,
        /** @var StringPrimitive|string|null path XPath or JSONPath expression */
        public StringPrimitive|string|null $path = null,
        /** @var TestScriptRequestMethodCodeType|null requestMethod delete | get | options | patch | post | put | head */
        public ?TestScriptRequestMethodCodeType $requestMethod = null,
        /** @var StringPrimitive|string|null requestURL Request URL comparison value */
        public StringPrimitive|string|null $requestURL = null,
        /** @var FHIRDefinedTypeType|null resource Resource type */
        public ?FHIRDefinedTypeType $resource = null,
        /** @var AssertionResponseTypesType|null response okay | created | noContent | notModified | bad | forbidden | notFound | methodNotAllowed | conflict | gone | preconditionFailed | unprocessable */
        public ?AssertionResponseTypesType $response = null,
        /** @var StringPrimitive|string|null responseCode HTTP response code to test */
        public StringPrimitive|string|null $responseCode = null,
        /** @var IdPrimitive|null sourceId Fixture Id of source expression or headerField */
        public ?IdPrimitive $sourceId = null,
        /** @var IdPrimitive|null validateProfileId Profile Id of validation profile reference */
        public ?IdPrimitive $validateProfileId = null,
        /** @var StringPrimitive|string|null value The value to compare to */
        public StringPrimitive|string|null $value = null,
        /** @var bool|null warningOnly Will this assert produce a warning only on error? */
        #[NotBlank]
        public ?bool $warningOnly = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
