<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
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
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.String',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'modifierExtension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'modifierExtension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'label' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'description' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'direction' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'compareToSourceId' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'compareToSourceExpression' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'compareToSourcePath' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'contentType' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'expression' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'headerField' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'minimumId' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'navigationLinks' => [
            'fhirType'     => 'boolean',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'operator' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'path' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'requestMethod' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'requestURL' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'resource' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'response' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'responseCode' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'sourceId' => [
            'fhirType'     => 'id',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'validateProfileId' => [
            'fhirType'     => 'id',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'value' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'warningOnly' => [
            'fhirType'     => 'boolean',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null label Tracking/logging assertion label */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $label = null,
        /** @var StringPrimitive|string|null description Tracking/reporting assertion description */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var AssertionDirectionTypeType|null direction response | request */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
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
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
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
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AssertionOperatorTypeType $operator = null,
        /** @var StringPrimitive|string|null path XPath or JSONPath expression */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $path = null,
        /** @var TestScriptRequestMethodCodeType|null requestMethod delete | get | options | patch | post | put | head */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?TestScriptRequestMethodCodeType $requestMethod = null,
        /** @var StringPrimitive|string|null requestURL Request URL comparison value */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $requestURL = null,
        /** @var FHIRDefinedTypeType|null resource Resource type */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?FHIRDefinedTypeType $resource = null,
        /** @var AssertionResponseTypesType|null response okay | created | noContent | notModified | bad | forbidden | notFound | methodNotAllowed | conflict | gone | preconditionFailed | unprocessable */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
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
