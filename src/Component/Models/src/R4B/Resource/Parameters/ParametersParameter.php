<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Parameters;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Contributor;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Count;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\DataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Distance;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Dosage;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ParameterDefinition;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\RelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\SampledData;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Signature;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\TriggerDefinition;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\OidPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UrlPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UuidPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ResourceResource;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A parameter passed to or received from the operation.
 */
#[FHIRBackboneElement(parentResource: 'Parameters', elementPath: 'Parameters.parameter', fhirVersion: 'R4B')]
class ParametersParameter extends BackboneElement
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
        'name' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'valueX' => [
            'fhirType'     => 'choice',
            'propertyKind' => 'choice',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => true,
            'jsonKey'      => null,
            'variants'     => [
                [
                    'fhirType'     => 'base64Binary',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\Base64BinaryPrimitive',
                    'jsonKey'      => 'valueBase64Binary',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'boolean',
                    'propertyKind' => 'scalar',
                    'phpType'      => 'bool',
                    'jsonKey'      => 'valueBoolean',
                    'isBuiltin'    => true,
                ],
                [
                    'fhirType'     => 'canonical',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'valueCanonical',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'code',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive',
                    'jsonKey'      => 'valueCode',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive',
                    'jsonKey'      => 'valueDate',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'valueDateTime',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'decimal',
                    'propertyKind' => 'scalar',
                    'phpType'      => 'float',
                    'jsonKey'      => 'valueDecimal',
                    'isBuiltin'    => true,
                ],
                [
                    'fhirType'     => 'id',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\IdPrimitive',
                    'jsonKey'      => 'valueId',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'instant',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\InstantPrimitive',
                    'jsonKey'      => 'valueInstant',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'integer',
                    'propertyKind' => 'scalar',
                    'phpType'      => 'int',
                    'jsonKey'      => 'valueInteger',
                    'isBuiltin'    => true,
                ],
                [
                    'fhirType'     => 'markdown',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\MarkdownPrimitive',
                    'jsonKey'      => 'valueMarkdown',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'oid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\OidPrimitive',
                    'jsonKey'      => 'valueOid',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'valuePositiveInt',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
                    'jsonKey'      => 'valueString',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'time',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive',
                    'jsonKey'      => 'valueTime',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'unsignedInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive',
                    'jsonKey'      => 'valueUnsignedInt',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'uri',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive',
                    'jsonKey'      => 'valueUri',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'url',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UrlPrimitive',
                    'jsonKey'      => 'valueUrl',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'uuid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UuidPrimitive',
                    'jsonKey'      => 'valueUuid',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Address',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Address',
                    'jsonKey'      => 'valueAddress',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Age',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Age',
                    'jsonKey'      => 'valueAge',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Annotation',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation',
                    'jsonKey'      => 'valueAnnotation',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Attachment',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Attachment',
                    'jsonKey'      => 'valueAttachment',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
                    'jsonKey'      => 'valueCodeableConcept',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding',
                    'jsonKey'      => 'valueCoding',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'ContactPoint',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactPoint',
                    'jsonKey'      => 'valueContactPoint',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Count',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Count',
                    'jsonKey'      => 'valueCount',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Distance',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Distance',
                    'jsonKey'      => 'valueDistance',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration',
                    'jsonKey'      => 'valueDuration',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'HumanName',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\HumanName',
                    'jsonKey'      => 'valueHumanName',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Identifier',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier',
                    'jsonKey'      => 'valueIdentifier',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Money',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money',
                    'jsonKey'      => 'valueMoney',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
                    'jsonKey'      => 'valuePeriod',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity',
                    'jsonKey'      => 'valueQuantity',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Range',
                    'jsonKey'      => 'valueRange',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Ratio',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio',
                    'jsonKey'      => 'valueRatio',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
                    'jsonKey'      => 'valueReference',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'SampledData',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\SampledData',
                    'jsonKey'      => 'valueSampledData',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Signature',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Signature',
                    'jsonKey'      => 'valueSignature',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Timing',
                    'jsonKey'      => 'valueTiming',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'ContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactDetail',
                    'jsonKey'      => 'valueContactDetail',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Contributor',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Contributor',
                    'jsonKey'      => 'valueContributor',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'DataRequirement',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\DataRequirement',
                    'jsonKey'      => 'valueDataRequirement',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Expression',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Expression',
                    'jsonKey'      => 'valueExpression',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'ParameterDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ParameterDefinition',
                    'jsonKey'      => 'valueParameterDefinition',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'RelatedArtifact',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\RelatedArtifact',
                    'jsonKey'      => 'valueRelatedArtifact',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'TriggerDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\TriggerDefinition',
                    'jsonKey'      => 'valueTriggerDefinition',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'UsageContext',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\UsageContext',
                    'jsonKey'      => 'valueUsageContext',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Dosage',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Dosage',
                    'jsonKey'      => 'valueDosage',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Meta',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta',
                    'jsonKey'      => 'valueMeta',
                    'isBuiltin'    => false,
                ],
            ],
        ],
        'resource' => [
            'fhirType'     => 'Resource',
            'propertyKind' => 'resource',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'part' => [
            'fhirType'     => 'unknown',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
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
        /** @var StringPrimitive|string|null name Name from the definition */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|float|IdPrimitive|InstantPrimitive|int|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|string|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|Reference|SampledData|Signature|Timing|ContactDetail|Contributor|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Dosage|Meta|null valueX If parameter is a data type */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'base64Binary',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\Base64BinaryPrimitive',
                    'jsonKey'      => 'valueBase64Binary',
                ],
                ['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'valueBoolean'],
                [
                    'fhirType'     => 'canonical',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'valueCanonical',
                ],
                [
                    'fhirType'     => 'code',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive',
                    'jsonKey'      => 'valueCode',
                ],
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive',
                    'jsonKey'      => 'valueDate',
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'valueDateTime',
                ],
                ['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'float', 'jsonKey' => 'valueDecimal'],
                [
                    'fhirType'     => 'id',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\IdPrimitive',
                    'jsonKey'      => 'valueId',
                ],
                [
                    'fhirType'     => 'instant',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\InstantPrimitive',
                    'jsonKey'      => 'valueInstant',
                ],
                ['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'valueInteger'],
                [
                    'fhirType'     => 'markdown',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\MarkdownPrimitive',
                    'jsonKey'      => 'valueMarkdown',
                ],
                [
                    'fhirType'     => 'oid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\OidPrimitive',
                    'jsonKey'      => 'valueOid',
                ],
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'valuePositiveInt',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
                    'jsonKey'      => 'valueString',
                ],
                [
                    'fhirType'     => 'time',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive',
                    'jsonKey'      => 'valueTime',
                ],
                [
                    'fhirType'     => 'unsignedInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive',
                    'jsonKey'      => 'valueUnsignedInt',
                ],
                [
                    'fhirType'     => 'uri',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive',
                    'jsonKey'      => 'valueUri',
                ],
                [
                    'fhirType'     => 'url',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UrlPrimitive',
                    'jsonKey'      => 'valueUrl',
                ],
                [
                    'fhirType'     => 'uuid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UuidPrimitive',
                    'jsonKey'      => 'valueUuid',
                ],
                [
                    'fhirType'     => 'Address',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Address',
                    'jsonKey'      => 'valueAddress',
                ],
                [
                    'fhirType'     => 'Age',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Age',
                    'jsonKey'      => 'valueAge',
                ],
                [
                    'fhirType'     => 'Annotation',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation',
                    'jsonKey'      => 'valueAnnotation',
                ],
                [
                    'fhirType'     => 'Attachment',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Attachment',
                    'jsonKey'      => 'valueAttachment',
                ],
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
                    'jsonKey'      => 'valueCodeableConcept',
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding',
                    'jsonKey'      => 'valueCoding',
                ],
                [
                    'fhirType'     => 'ContactPoint',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactPoint',
                    'jsonKey'      => 'valueContactPoint',
                ],
                [
                    'fhirType'     => 'Count',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Count',
                    'jsonKey'      => 'valueCount',
                ],
                [
                    'fhirType'     => 'Distance',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Distance',
                    'jsonKey'      => 'valueDistance',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration',
                    'jsonKey'      => 'valueDuration',
                ],
                [
                    'fhirType'     => 'HumanName',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\HumanName',
                    'jsonKey'      => 'valueHumanName',
                ],
                [
                    'fhirType'     => 'Identifier',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier',
                    'jsonKey'      => 'valueIdentifier',
                ],
                [
                    'fhirType'     => 'Money',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money',
                    'jsonKey'      => 'valueMoney',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
                    'jsonKey'      => 'valuePeriod',
                ],
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity',
                    'jsonKey'      => 'valueQuantity',
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Range',
                    'jsonKey'      => 'valueRange',
                ],
                [
                    'fhirType'     => 'Ratio',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio',
                    'jsonKey'      => 'valueRatio',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
                    'jsonKey'      => 'valueReference',
                ],
                [
                    'fhirType'     => 'SampledData',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\SampledData',
                    'jsonKey'      => 'valueSampledData',
                ],
                [
                    'fhirType'     => 'Signature',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Signature',
                    'jsonKey'      => 'valueSignature',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Timing',
                    'jsonKey'      => 'valueTiming',
                ],
                [
                    'fhirType'     => 'ContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactDetail',
                    'jsonKey'      => 'valueContactDetail',
                ],
                [
                    'fhirType'     => 'Contributor',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Contributor',
                    'jsonKey'      => 'valueContributor',
                ],
                [
                    'fhirType'     => 'DataRequirement',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\DataRequirement',
                    'jsonKey'      => 'valueDataRequirement',
                ],
                [
                    'fhirType'     => 'Expression',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Expression',
                    'jsonKey'      => 'valueExpression',
                ],
                [
                    'fhirType'     => 'ParameterDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ParameterDefinition',
                    'jsonKey'      => 'valueParameterDefinition',
                ],
                [
                    'fhirType'     => 'RelatedArtifact',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\RelatedArtifact',
                    'jsonKey'      => 'valueRelatedArtifact',
                ],
                [
                    'fhirType'     => 'TriggerDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\TriggerDefinition',
                    'jsonKey'      => 'valueTriggerDefinition',
                ],
                [
                    'fhirType'     => 'UsageContext',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\UsageContext',
                    'jsonKey'      => 'valueUsageContext',
                ],
                [
                    'fhirType'     => 'Dosage',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Dosage',
                    'jsonKey'      => 'valueDosage',
                ],
                [
                    'fhirType'     => 'Meta',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta',
                    'jsonKey'      => 'valueMeta',
                ],
            ],
        )]
        public Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|float|IdPrimitive|InstantPrimitive|int|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|string|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|Reference|SampledData|Signature|Timing|ContactDetail|Contributor|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Dosage|Meta|null $valueX = null,
        /** @var ResourceResource|null resource If parameter is a whole resource */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource')]
        public ?ResourceResource $resource = null,
        /** @var array<ParametersParameter> part Named part of a multi-part parameter */
        #[FhirProperty(fhirType: 'unknown', propertyKind: 'complex', isArray: true)]
        public array $part = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
