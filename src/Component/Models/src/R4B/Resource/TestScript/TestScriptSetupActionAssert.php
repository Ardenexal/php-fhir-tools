<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\TestScript;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\AssertionDirectionTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\AssertionOperatorTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\AssertionResponseTypesType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDefinedTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\MimeTypesType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\TestScriptRequestMethodCodeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Evaluates the results of previous operations to determine if the server under test behaves appropriately.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.setup.action.assert', fhirVersion: 'R4B')]
#[FHIRPathInvariant(
    key: 'tst-5',
    severity: 'error',
    expression: 'extension.exists() or (contentType.count() + expression.count() + headerField.count() + minimumId.count() + navigationLinks.count() + path.count() + requestMethod.count() + resource.count() + responseCode.count() + response.count()  + validateProfileId.count() <=1)',
    human: 'Only a single assertion SHALL be present within setup action assert element.',
)]
#[FHIRPathInvariant(
    key: 'tst-10',
    severity: 'error',
    expression: 'compareToSourceId.empty() xor (compareToSourceExpression.exists() or compareToSourcePath.exists())',
    human: 'Setup action assert SHALL contain either compareToSourceId and compareToSourceExpression, compareToSourceId and compareToSourcePath or neither.',
)]
#[FHIRPathInvariant(
    key: 'tst-12',
    severity: 'error',
    expression: '(response.empty() and responseCode.empty() and direction = \'request\') or direction.empty() or direction = \'response\'',
    human: 'Setup action assert response and responseCode SHALL be empty when direction equals request',
)]
class TestScriptSetupActionAssert extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null label Tracking/logging assertion label */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $label = null,
        /** @var StringPrimitive|string|null description Tracking/reporting assertion description */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var AssertionDirectionTypeType|null direction response | request */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/assert-direction-codes|4.3.0', strength: 'required')]
        public ?AssertionDirectionTypeType $direction = null,
        /** @var StringPrimitive|string|null compareToSourceId Id of the source fixture to be evaluated */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $compareToSourceId = null,
        /** @var StringPrimitive|string|null compareToSourceExpression The FHIRPath expression to evaluate against the source fixture */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $compareToSourceExpression = null,
        /** @var StringPrimitive|string|null compareToSourcePath XPath or JSONPath expression to evaluate against the source fixture */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $compareToSourcePath = null,
        /** @var MimeTypesType|null contentType Mime type to compare against the 'Content-Type' header */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/mimetypes|4.3.0', strength: 'required')]
        public ?MimeTypesType $contentType = null,
        /** @var StringPrimitive|string|null expression The FHIRPath expression to be evaluated */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $expression = null,
        /** @var StringPrimitive|string|null headerField HTTP header field name */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $headerField = null,
        /** @var StringPrimitive|string|null minimumId Fixture Id of minimum content resource */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $minimumId = null,
        /** @var bool|null navigationLinks Perform validation on navigation links? */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $navigationLinks = null,
        /** @var AssertionOperatorTypeType|null operator equals | notEquals | in | notIn | greaterThan | lessThan | empty | notEmpty | contains | notContains | eval */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/assert-operator-codes|4.3.0', strength: 'required')]
        public ?AssertionOperatorTypeType $operator = null,
        /** @var StringPrimitive|string|null path XPath or JSONPath expression */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $path = null,
        /** @var TestScriptRequestMethodCodeType|null requestMethod delete | get | options | patch | post | put | head */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/http-operations|4.3.0', strength: 'required')]
        public ?TestScriptRequestMethodCodeType $requestMethod = null,
        /** @var StringPrimitive|string|null requestURL Request URL comparison value */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $requestURL = null,
        /** @var FHIRDefinedTypeType|null resource Resource type */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/defined-types|4.3.0', strength: 'required')]
        public ?FHIRDefinedTypeType $resource = null,
        /** @var AssertionResponseTypesType|null response okay | created | noContent | notModified | bad | forbidden | notFound | methodNotAllowed | conflict | gone | preconditionFailed | unprocessable */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/assert-response-code-types|4.3.0', strength: 'required')]
        public ?AssertionResponseTypesType $response = null,
        /** @var StringPrimitive|string|null responseCode HTTP response code to test */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $responseCode = null,
        /** @var IdPrimitive|null sourceId Fixture Id of source expression or headerField */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $sourceId = null,
        /** @var IdPrimitive|null validateProfileId Profile Id of validation profile reference */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $validateProfileId = null,
        /** @var StringPrimitive|string|null value The value to compare to */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $value = null,
        /** @var bool|null warningOnly Will this assert produce a warning only on error? */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?bool $warningOnly = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
