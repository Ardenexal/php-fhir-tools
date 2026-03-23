<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Contributor;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Count;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Distance;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Dosage;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ParameterDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SampledData;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureMapSourceListModeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TriggerDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
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
 * @description Source inputs to the mapping.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.source', fhirVersion: 'R4')]
class StructureMapGroupRuleSource extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var IdPrimitive|null context Type or variable this rule applies to */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?IdPrimitive $context = null,
        /** @var int|null min Specified minimum cardinality */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $min = null,
        /** @var StringPrimitive|string|null max Specified maximum cardinality (number or *) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $max = null,
        /** @var StringPrimitive|string|null type Rule only applies if source has this type */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $type = null,
        /** @var Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|string|IdPrimitive|InstantPrimitive|int|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|Reference|SampledData|Signature|Timing|ContactDetail|Contributor|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Dosage|Meta|null defaultValue Default value if no value exists */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'base64Binary',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive',
                    'jsonKey'      => 'defaultValueBase64Binary',
                ],
                ['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'defaultValueBoolean'],
                [
                    'fhirType'     => 'canonical',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'defaultValueCanonical',
                ],
                [
                    'fhirType'     => 'code',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive',
                    'jsonKey'      => 'defaultValueCode',
                ],
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive',
                    'jsonKey'      => 'defaultValueDate',
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'defaultValueDateTime',
                ],
                ['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'string', 'jsonKey' => 'defaultValueDecimal'],
                [
                    'fhirType'     => 'id',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive',
                    'jsonKey'      => 'defaultValueId',
                ],
                [
                    'fhirType'     => 'instant',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive',
                    'jsonKey'      => 'defaultValueInstant',
                ],
                ['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'defaultValueInteger'],
                [
                    'fhirType'     => 'markdown',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive',
                    'jsonKey'      => 'defaultValueMarkdown',
                ],
                [
                    'fhirType'     => 'oid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\OidPrimitive',
                    'jsonKey'      => 'defaultValueOid',
                ],
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'defaultValuePositiveInt',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive',
                    'jsonKey'      => 'defaultValueString',
                ],
                [
                    'fhirType'     => 'time',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive',
                    'jsonKey'      => 'defaultValueTime',
                ],
                [
                    'fhirType'     => 'unsignedInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive',
                    'jsonKey'      => 'defaultValueUnsignedInt',
                ],
                [
                    'fhirType'     => 'uri',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive',
                    'jsonKey'      => 'defaultValueUri',
                ],
                [
                    'fhirType'     => 'url',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive',
                    'jsonKey'      => 'defaultValueUrl',
                ],
                [
                    'fhirType'     => 'uuid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UuidPrimitive',
                    'jsonKey'      => 'defaultValueUuid',
                ],
                [
                    'fhirType'     => 'Address',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Address',
                    'jsonKey'      => 'defaultValueAddress',
                ],
                [
                    'fhirType'     => 'Age',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Age',
                    'jsonKey'      => 'defaultValueAge',
                ],
                [
                    'fhirType'     => 'Annotation',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation',
                    'jsonKey'      => 'defaultValueAnnotation',
                ],
                [
                    'fhirType'     => 'Attachment',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment',
                    'jsonKey'      => 'defaultValueAttachment',
                ],
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
                    'jsonKey'      => 'defaultValueCodeableConcept',
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding',
                    'jsonKey'      => 'defaultValueCoding',
                ],
                [
                    'fhirType'     => 'ContactPoint',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint',
                    'jsonKey'      => 'defaultValueContactPoint',
                ],
                [
                    'fhirType'     => 'Count',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Count',
                    'jsonKey'      => 'defaultValueCount',
                ],
                [
                    'fhirType'     => 'Distance',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Distance',
                    'jsonKey'      => 'defaultValueDistance',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration',
                    'jsonKey'      => 'defaultValueDuration',
                ],
                [
                    'fhirType'     => 'HumanName',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName',
                    'jsonKey'      => 'defaultValueHumanName',
                ],
                [
                    'fhirType'     => 'Identifier',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier',
                    'jsonKey'      => 'defaultValueIdentifier',
                ],
                [
                    'fhirType'     => 'Money',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Money',
                    'jsonKey'      => 'defaultValueMoney',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'defaultValuePeriod',
                ],
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity',
                    'jsonKey'      => 'defaultValueQuantity',
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Range',
                    'jsonKey'      => 'defaultValueRange',
                ],
                [
                    'fhirType'     => 'Ratio',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio',
                    'jsonKey'      => 'defaultValueRatio',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
                    'jsonKey'      => 'defaultValueReference',
                ],
                [
                    'fhirType'     => 'SampledData',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\SampledData',
                    'jsonKey'      => 'defaultValueSampledData',
                ],
                [
                    'fhirType'     => 'Signature',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature',
                    'jsonKey'      => 'defaultValueSignature',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing',
                    'jsonKey'      => 'defaultValueTiming',
                ],
                [
                    'fhirType'     => 'ContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
                    'jsonKey'      => 'defaultValueContactDetail',
                ],
                [
                    'fhirType'     => 'Contributor',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Contributor',
                    'jsonKey'      => 'defaultValueContributor',
                ],
                [
                    'fhirType'     => 'DataRequirement',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement',
                    'jsonKey'      => 'defaultValueDataRequirement',
                ],
                [
                    'fhirType'     => 'Expression',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression',
                    'jsonKey'      => 'defaultValueExpression',
                ],
                [
                    'fhirType'     => 'ParameterDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ParameterDefinition',
                    'jsonKey'      => 'defaultValueParameterDefinition',
                ],
                [
                    'fhirType'     => 'RelatedArtifact',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact',
                    'jsonKey'      => 'defaultValueRelatedArtifact',
                ],
                [
                    'fhirType'     => 'TriggerDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\TriggerDefinition',
                    'jsonKey'      => 'defaultValueTriggerDefinition',
                ],
                [
                    'fhirType'     => 'UsageContext',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext',
                    'jsonKey'      => 'defaultValueUsageContext',
                ],
                [
                    'fhirType'     => 'Dosage',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Dosage',
                    'jsonKey'      => 'defaultValueDosage',
                ],
                [
                    'fhirType'     => 'Meta',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta',
                    'jsonKey'      => 'defaultValueMeta',
                ],
            ],
        )]
        public Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|string|IdPrimitive|InstantPrimitive|int|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|Reference|SampledData|Signature|Timing|ContactDetail|Contributor|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Dosage|Meta|null $defaultValue = null,
        /** @var StringPrimitive|string|null element Optional field for this source */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $element = null,
        /** @var StructureMapSourceListModeType|null listMode first | not_first | last | not_last | only_one */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?StructureMapSourceListModeType $listMode = null,
        /** @var IdPrimitive|null variable Named context for field, if a field is specified */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $variable = null,
        /** @var StringPrimitive|string|null condition FHIRPath expression  - must be true or the rule does not apply */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $condition = null,
        /** @var StringPrimitive|string|null check FHIRPath expression  - must be true or the mapping engine throws an error instead of completing */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $check = null,
        /** @var StringPrimitive|string|null logMessage Message to put in log if source exists (FHIRPath) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $logMessage = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
