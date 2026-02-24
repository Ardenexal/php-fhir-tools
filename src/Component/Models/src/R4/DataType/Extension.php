<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\OidPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UuidPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Extension
 *
 * @description Optional Extension Element - found in all resources.
 */
#[FHIRComplexType(typeName: 'Extension', fhirVersion: 'R4')]
class Extension extends Element
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
        'url' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.String',
            'propertyKind' => 'scalar',
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
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive',
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
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'valueCanonical',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'code',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive',
                    'jsonKey'      => 'valueCode',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive',
                    'jsonKey'      => 'valueDate',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive',
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
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive',
                    'jsonKey'      => 'valueId',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'instant',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive',
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
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive',
                    'jsonKey'      => 'valueMarkdown',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'oid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\OidPrimitive',
                    'jsonKey'      => 'valueOid',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'valuePositiveInt',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive',
                    'jsonKey'      => 'valueString',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'time',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive',
                    'jsonKey'      => 'valueTime',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'unsignedInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive',
                    'jsonKey'      => 'valueUnsignedInt',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'uri',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive',
                    'jsonKey'      => 'valueUri',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'url',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive',
                    'jsonKey'      => 'valueUrl',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'uuid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UuidPrimitive',
                    'jsonKey'      => 'valueUuid',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Address',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Address',
                    'jsonKey'      => 'valueAddress',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Age',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Age',
                    'jsonKey'      => 'valueAge',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Annotation',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation',
                    'jsonKey'      => 'valueAnnotation',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Attachment',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment',
                    'jsonKey'      => 'valueAttachment',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
                    'jsonKey'      => 'valueCodeableConcept',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding',
                    'jsonKey'      => 'valueCoding',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'ContactPoint',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint',
                    'jsonKey'      => 'valueContactPoint',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Count',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Count',
                    'jsonKey'      => 'valueCount',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Distance',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Distance',
                    'jsonKey'      => 'valueDistance',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration',
                    'jsonKey'      => 'valueDuration',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'HumanName',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName',
                    'jsonKey'      => 'valueHumanName',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Identifier',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier',
                    'jsonKey'      => 'valueIdentifier',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Money',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Money',
                    'jsonKey'      => 'valueMoney',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'valuePeriod',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity',
                    'jsonKey'      => 'valueQuantity',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Range',
                    'jsonKey'      => 'valueRange',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Ratio',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio',
                    'jsonKey'      => 'valueRatio',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
                    'jsonKey'      => 'valueReference',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'SampledData',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\SampledData',
                    'jsonKey'      => 'valueSampledData',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Signature',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature',
                    'jsonKey'      => 'valueSignature',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing',
                    'jsonKey'      => 'valueTiming',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'ContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
                    'jsonKey'      => 'valueContactDetail',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Contributor',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Contributor',
                    'jsonKey'      => 'valueContributor',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'DataRequirement',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement',
                    'jsonKey'      => 'valueDataRequirement',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Expression',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression',
                    'jsonKey'      => 'valueExpression',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'ParameterDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ParameterDefinition',
                    'jsonKey'      => 'valueParameterDefinition',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'RelatedArtifact',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact',
                    'jsonKey'      => 'valueRelatedArtifact',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'TriggerDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\TriggerDefinition',
                    'jsonKey'      => 'valueTriggerDefinition',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'UsageContext',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext',
                    'jsonKey'      => 'valueUsageContext',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Dosage',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Dosage',
                    'jsonKey'      => 'valueDosage',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Meta',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta',
                    'jsonKey'      => 'valueMeta',
                    'isBuiltin'    => false,
                ],
            ],
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var string|null url identifies the meaning of the extension */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?string $url = null,
        /** @var Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|float|IdPrimitive|InstantPrimitive|int|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|string|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|Reference|SampledData|Signature|Timing|ContactDetail|Contributor|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Dosage|Meta|null valueX Value of extension */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'base64Binary',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive',
                    'jsonKey'      => 'valueBase64Binary',
                ],
                ['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'valueBoolean'],
                [
                    'fhirType'     => 'canonical',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'valueCanonical',
                ],
                [
                    'fhirType'     => 'code',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive',
                    'jsonKey'      => 'valueCode',
                ],
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive',
                    'jsonKey'      => 'valueDate',
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'valueDateTime',
                ],
                ['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'float', 'jsonKey' => 'valueDecimal'],
                [
                    'fhirType'     => 'id',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive',
                    'jsonKey'      => 'valueId',
                ],
                [
                    'fhirType'     => 'instant',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive',
                    'jsonKey'      => 'valueInstant',
                ],
                ['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'valueInteger'],
                [
                    'fhirType'     => 'markdown',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive',
                    'jsonKey'      => 'valueMarkdown',
                ],
                [
                    'fhirType'     => 'oid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\OidPrimitive',
                    'jsonKey'      => 'valueOid',
                ],
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'valuePositiveInt',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive',
                    'jsonKey'      => 'valueString',
                ],
                [
                    'fhirType'     => 'time',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive',
                    'jsonKey'      => 'valueTime',
                ],
                [
                    'fhirType'     => 'unsignedInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive',
                    'jsonKey'      => 'valueUnsignedInt',
                ],
                [
                    'fhirType'     => 'uri',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive',
                    'jsonKey'      => 'valueUri',
                ],
                [
                    'fhirType'     => 'url',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive',
                    'jsonKey'      => 'valueUrl',
                ],
                [
                    'fhirType'     => 'uuid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UuidPrimitive',
                    'jsonKey'      => 'valueUuid',
                ],
                [
                    'fhirType'     => 'Address',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Address',
                    'jsonKey'      => 'valueAddress',
                ],
                [
                    'fhirType'     => 'Age',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Age',
                    'jsonKey'      => 'valueAge',
                ],
                [
                    'fhirType'     => 'Annotation',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation',
                    'jsonKey'      => 'valueAnnotation',
                ],
                [
                    'fhirType'     => 'Attachment',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment',
                    'jsonKey'      => 'valueAttachment',
                ],
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
                    'jsonKey'      => 'valueCodeableConcept',
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding',
                    'jsonKey'      => 'valueCoding',
                ],
                [
                    'fhirType'     => 'ContactPoint',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint',
                    'jsonKey'      => 'valueContactPoint',
                ],
                [
                    'fhirType'     => 'Count',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Count',
                    'jsonKey'      => 'valueCount',
                ],
                [
                    'fhirType'     => 'Distance',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Distance',
                    'jsonKey'      => 'valueDistance',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration',
                    'jsonKey'      => 'valueDuration',
                ],
                [
                    'fhirType'     => 'HumanName',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName',
                    'jsonKey'      => 'valueHumanName',
                ],
                [
                    'fhirType'     => 'Identifier',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier',
                    'jsonKey'      => 'valueIdentifier',
                ],
                [
                    'fhirType'     => 'Money',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Money',
                    'jsonKey'      => 'valueMoney',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'valuePeriod',
                ],
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity',
                    'jsonKey'      => 'valueQuantity',
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Range',
                    'jsonKey'      => 'valueRange',
                ],
                [
                    'fhirType'     => 'Ratio',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio',
                    'jsonKey'      => 'valueRatio',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
                    'jsonKey'      => 'valueReference',
                ],
                [
                    'fhirType'     => 'SampledData',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\SampledData',
                    'jsonKey'      => 'valueSampledData',
                ],
                [
                    'fhirType'     => 'Signature',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature',
                    'jsonKey'      => 'valueSignature',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing',
                    'jsonKey'      => 'valueTiming',
                ],
                [
                    'fhirType'     => 'ContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
                    'jsonKey'      => 'valueContactDetail',
                ],
                [
                    'fhirType'     => 'Contributor',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Contributor',
                    'jsonKey'      => 'valueContributor',
                ],
                [
                    'fhirType'     => 'DataRequirement',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement',
                    'jsonKey'      => 'valueDataRequirement',
                ],
                [
                    'fhirType'     => 'Expression',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression',
                    'jsonKey'      => 'valueExpression',
                ],
                [
                    'fhirType'     => 'ParameterDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ParameterDefinition',
                    'jsonKey'      => 'valueParameterDefinition',
                ],
                [
                    'fhirType'     => 'RelatedArtifact',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact',
                    'jsonKey'      => 'valueRelatedArtifact',
                ],
                [
                    'fhirType'     => 'TriggerDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\TriggerDefinition',
                    'jsonKey'      => 'valueTriggerDefinition',
                ],
                [
                    'fhirType'     => 'UsageContext',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext',
                    'jsonKey'      => 'valueUsageContext',
                ],
                [
                    'fhirType'     => 'Dosage',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Dosage',
                    'jsonKey'      => 'valueDosage',
                ],
                [
                    'fhirType'     => 'Meta',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta',
                    'jsonKey'      => 'valueMeta',
                ],
            ],
        )]
        public Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|float|IdPrimitive|InstantPrimitive|int|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|string|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|Reference|SampledData|Signature|Timing|ContactDetail|Contributor|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Dosage|Meta|null $valueX = null,
    ) {
        parent::__construct($id, $extension);
    }
}
