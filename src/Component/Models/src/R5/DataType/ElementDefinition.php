<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\Integer64Primitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\OidPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\TimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UuidPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ElementDefinition
 *
 * @description Captures constraints on each element within the resource, profile, or extension.
 */
#[FHIRComplexType(typeName: 'ElementDefinition', fhirVersion: 'R5')]
class ElementDefinition extends BackboneType
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
        /** @var StringPrimitive|string|null path Path of the element in the hierarchy of elements */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $path = null,
        /** @var array<PropertyRepresentationType> representation xmlAttr | xmlText | typeAttr | cdaText | xhtml */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $representation = [],
        /** @var StringPrimitive|string|null sliceName Name for this particular element (in a set of slices) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $sliceName = null,
        /** @var bool|null sliceIsConstraining If this slice definition constrains an inherited slice definition (or not) */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $sliceIsConstraining = null,
        /** @var StringPrimitive|string|null label Name for element to display with or prompt for element */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $label = null,
        /** @var array<Coding> code Corresponding codes in terminologies */
        #[FhirProperty(
            fhirType: 'Coding',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
        )]
        public array $code = [],
        /** @var ElementDefinitionSlicing|null slicing This element is sliced - slices follow */
        #[FhirProperty(fhirType: 'Element', propertyKind: 'complex')]
        public ?ElementDefinitionSlicing $slicing = null,
        /** @var StringPrimitive|string|null short Concise definition for space-constrained presentation */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $short = null,
        /** @var MarkdownPrimitive|null definition Full formal definition as narrative text */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $definition = null,
        /** @var MarkdownPrimitive|null comment Comments about the use of this element */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $comment = null,
        /** @var MarkdownPrimitive|null requirements Why this resource has been created */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $requirements = null,
        /** @var array<StringPrimitive|string> alias Other names */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $alias = [],
        /** @var UnsignedIntPrimitive|null min Minimum Cardinality */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $min = null,
        /** @var StringPrimitive|string|null max Maximum Cardinality (a number or *) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $max = null,
        /** @var ElementDefinitionBase|null base Base definition information for tools */
        #[FhirProperty(fhirType: 'Element', propertyKind: 'complex')]
        public ?ElementDefinitionBase $base = null,
        /** @var UriPrimitive|null contentReference Reference to definition of content for the element */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $contentReference = null,
        /** @var array<ElementDefinitionType> type Data type and Profile for this element */
        #[FhirProperty(
            fhirType: 'Element',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ElementDefinitionType',
        )]
        public array $type = [],
        /** @var Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|string|IdPrimitive|InstantPrimitive|int|Integer64Primitive|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|CodeableReference|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|RatioRange|Reference|SampledData|Signature|Timing|ContactDetail|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Availability|ExtendedContactDetail|Dosage|Meta|null defaultValue Specified value if missing from instance */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'base64Binary',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\Base64BinaryPrimitive',
                    'jsonKey'      => 'defaultValueBase64Binary',
                ],
                ['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'defaultValueBoolean'],
                [
                    'fhirType'     => 'canonical',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'defaultValueCanonical',
                ],
                [
                    'fhirType'     => 'code',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive',
                    'jsonKey'      => 'defaultValueCode',
                ],
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive',
                    'jsonKey'      => 'defaultValueDate',
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'defaultValueDateTime',
                ],
                ['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'string', 'jsonKey' => 'defaultValueDecimal'],
                [
                    'fhirType'     => 'id',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\IdPrimitive',
                    'jsonKey'      => 'defaultValueId',
                ],
                [
                    'fhirType'     => 'instant',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive',
                    'jsonKey'      => 'defaultValueInstant',
                ],
                ['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'defaultValueInteger'],
                [
                    'fhirType'     => 'integer64',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\Integer64Primitive',
                    'jsonKey'      => 'defaultValueInteger64',
                ],
                [
                    'fhirType'     => 'markdown',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive',
                    'jsonKey'      => 'defaultValueMarkdown',
                ],
                [
                    'fhirType'     => 'oid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\OidPrimitive',
                    'jsonKey'      => 'defaultValueOid',
                ],
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'defaultValuePositiveInt',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
                    'jsonKey'      => 'defaultValueString',
                ],
                [
                    'fhirType'     => 'time',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\TimePrimitive',
                    'jsonKey'      => 'defaultValueTime',
                ],
                [
                    'fhirType'     => 'unsignedInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive',
                    'jsonKey'      => 'defaultValueUnsignedInt',
                ],
                [
                    'fhirType'     => 'uri',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive',
                    'jsonKey'      => 'defaultValueUri',
                ],
                [
                    'fhirType'     => 'url',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive',
                    'jsonKey'      => 'defaultValueUrl',
                ],
                [
                    'fhirType'     => 'uuid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UuidPrimitive',
                    'jsonKey'      => 'defaultValueUuid',
                ],
                [
                    'fhirType'     => 'Address',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Address',
                    'jsonKey'      => 'defaultValueAddress',
                ],
                [
                    'fhirType'     => 'Age',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Age',
                    'jsonKey'      => 'defaultValueAge',
                ],
                [
                    'fhirType'     => 'Annotation',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation',
                    'jsonKey'      => 'defaultValueAnnotation',
                ],
                [
                    'fhirType'     => 'Attachment',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment',
                    'jsonKey'      => 'defaultValueAttachment',
                ],
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
                    'jsonKey'      => 'defaultValueCodeableConcept',
                ],
                [
                    'fhirType'     => 'CodeableReference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference',
                    'jsonKey'      => 'defaultValueCodeableReference',
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
                    'jsonKey'      => 'defaultValueCoding',
                ],
                [
                    'fhirType'     => 'ContactPoint',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint',
                    'jsonKey'      => 'defaultValueContactPoint',
                ],
                [
                    'fhirType'     => 'Count',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Count',
                    'jsonKey'      => 'defaultValueCount',
                ],
                [
                    'fhirType'     => 'Distance',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Distance',
                    'jsonKey'      => 'defaultValueDistance',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration',
                    'jsonKey'      => 'defaultValueDuration',
                ],
                [
                    'fhirType'     => 'HumanName',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\HumanName',
                    'jsonKey'      => 'defaultValueHumanName',
                ],
                [
                    'fhirType'     => 'Identifier',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
                    'jsonKey'      => 'defaultValueIdentifier',
                ],
                [
                    'fhirType'     => 'Money',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Money',
                    'jsonKey'      => 'defaultValueMoney',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
                    'jsonKey'      => 'defaultValuePeriod',
                ],
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity',
                    'jsonKey'      => 'defaultValueQuantity',
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Range',
                    'jsonKey'      => 'defaultValueRange',
                ],
                [
                    'fhirType'     => 'Ratio',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Ratio',
                    'jsonKey'      => 'defaultValueRatio',
                ],
                [
                    'fhirType'     => 'RatioRange',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\RatioRange',
                    'jsonKey'      => 'defaultValueRatioRange',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
                    'jsonKey'      => 'defaultValueReference',
                ],
                [
                    'fhirType'     => 'SampledData',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\SampledData',
                    'jsonKey'      => 'defaultValueSampledData',
                ],
                [
                    'fhirType'     => 'Signature',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Signature',
                    'jsonKey'      => 'defaultValueSignature',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Timing',
                    'jsonKey'      => 'defaultValueTiming',
                ],
                [
                    'fhirType'     => 'ContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactDetail',
                    'jsonKey'      => 'defaultValueContactDetail',
                ],
                [
                    'fhirType'     => 'DataRequirement',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\DataRequirement',
                    'jsonKey'      => 'defaultValueDataRequirement',
                ],
                [
                    'fhirType'     => 'Expression',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Expression',
                    'jsonKey'      => 'defaultValueExpression',
                ],
                [
                    'fhirType'     => 'ParameterDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ParameterDefinition',
                    'jsonKey'      => 'defaultValueParameterDefinition',
                ],
                [
                    'fhirType'     => 'RelatedArtifact',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\RelatedArtifact',
                    'jsonKey'      => 'defaultValueRelatedArtifact',
                ],
                [
                    'fhirType'     => 'TriggerDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\TriggerDefinition',
                    'jsonKey'      => 'defaultValueTriggerDefinition',
                ],
                [
                    'fhirType'     => 'UsageContext',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext',
                    'jsonKey'      => 'defaultValueUsageContext',
                ],
                [
                    'fhirType'     => 'Availability',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Availability',
                    'jsonKey'      => 'defaultValueAvailability',
                ],
                [
                    'fhirType'     => 'ExtendedContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail',
                    'jsonKey'      => 'defaultValueExtendedContactDetail',
                ],
                [
                    'fhirType'     => 'Dosage',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Dosage',
                    'jsonKey'      => 'defaultValueDosage',
                ],
                [
                    'fhirType'     => 'Meta',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta',
                    'jsonKey'      => 'defaultValueMeta',
                ],
            ],
        )]
        public Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|string|IdPrimitive|InstantPrimitive|int|Integer64Primitive|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|CodeableReference|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|RatioRange|Reference|SampledData|Signature|Timing|ContactDetail|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Availability|ExtendedContactDetail|Dosage|Meta|null $defaultValue = null,
        /** @var MarkdownPrimitive|null meaningWhenMissing Implicit meaning when this element is missing */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $meaningWhenMissing = null,
        /** @var StringPrimitive|string|null orderMeaning What the order of the elements means */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $orderMeaning = null,
        /** @var Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|string|IdPrimitive|InstantPrimitive|int|Integer64Primitive|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|CodeableReference|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|RatioRange|Reference|SampledData|Signature|Timing|ContactDetail|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Availability|ExtendedContactDetail|Dosage|Meta|null fixed Value must be exactly this */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'base64Binary',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\Base64BinaryPrimitive',
                    'jsonKey'      => 'fixedBase64Binary',
                ],
                ['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'fixedBoolean'],
                [
                    'fhirType'     => 'canonical',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'fixedCanonical',
                ],
                [
                    'fhirType'     => 'code',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive',
                    'jsonKey'      => 'fixedCode',
                ],
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive',
                    'jsonKey'      => 'fixedDate',
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'fixedDateTime',
                ],
                ['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'string', 'jsonKey' => 'fixedDecimal'],
                [
                    'fhirType'     => 'id',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\IdPrimitive',
                    'jsonKey'      => 'fixedId',
                ],
                [
                    'fhirType'     => 'instant',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive',
                    'jsonKey'      => 'fixedInstant',
                ],
                ['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'fixedInteger'],
                [
                    'fhirType'     => 'integer64',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\Integer64Primitive',
                    'jsonKey'      => 'fixedInteger64',
                ],
                [
                    'fhirType'     => 'markdown',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive',
                    'jsonKey'      => 'fixedMarkdown',
                ],
                [
                    'fhirType'     => 'oid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\OidPrimitive',
                    'jsonKey'      => 'fixedOid',
                ],
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'fixedPositiveInt',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
                    'jsonKey'      => 'fixedString',
                ],
                [
                    'fhirType'     => 'time',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\TimePrimitive',
                    'jsonKey'      => 'fixedTime',
                ],
                [
                    'fhirType'     => 'unsignedInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive',
                    'jsonKey'      => 'fixedUnsignedInt',
                ],
                [
                    'fhirType'     => 'uri',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive',
                    'jsonKey'      => 'fixedUri',
                ],
                [
                    'fhirType'     => 'url',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive',
                    'jsonKey'      => 'fixedUrl',
                ],
                [
                    'fhirType'     => 'uuid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UuidPrimitive',
                    'jsonKey'      => 'fixedUuid',
                ],
                [
                    'fhirType'     => 'Address',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Address',
                    'jsonKey'      => 'fixedAddress',
                ],
                [
                    'fhirType'     => 'Age',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Age',
                    'jsonKey'      => 'fixedAge',
                ],
                [
                    'fhirType'     => 'Annotation',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation',
                    'jsonKey'      => 'fixedAnnotation',
                ],
                [
                    'fhirType'     => 'Attachment',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment',
                    'jsonKey'      => 'fixedAttachment',
                ],
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
                    'jsonKey'      => 'fixedCodeableConcept',
                ],
                [
                    'fhirType'     => 'CodeableReference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference',
                    'jsonKey'      => 'fixedCodeableReference',
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
                    'jsonKey'      => 'fixedCoding',
                ],
                [
                    'fhirType'     => 'ContactPoint',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint',
                    'jsonKey'      => 'fixedContactPoint',
                ],
                [
                    'fhirType'     => 'Count',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Count',
                    'jsonKey'      => 'fixedCount',
                ],
                [
                    'fhirType'     => 'Distance',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Distance',
                    'jsonKey'      => 'fixedDistance',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration',
                    'jsonKey'      => 'fixedDuration',
                ],
                [
                    'fhirType'     => 'HumanName',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\HumanName',
                    'jsonKey'      => 'fixedHumanName',
                ],
                [
                    'fhirType'     => 'Identifier',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
                    'jsonKey'      => 'fixedIdentifier',
                ],
                [
                    'fhirType'     => 'Money',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Money',
                    'jsonKey'      => 'fixedMoney',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
                    'jsonKey'      => 'fixedPeriod',
                ],
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity',
                    'jsonKey'      => 'fixedQuantity',
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Range',
                    'jsonKey'      => 'fixedRange',
                ],
                [
                    'fhirType'     => 'Ratio',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Ratio',
                    'jsonKey'      => 'fixedRatio',
                ],
                [
                    'fhirType'     => 'RatioRange',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\RatioRange',
                    'jsonKey'      => 'fixedRatioRange',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
                    'jsonKey'      => 'fixedReference',
                ],
                [
                    'fhirType'     => 'SampledData',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\SampledData',
                    'jsonKey'      => 'fixedSampledData',
                ],
                [
                    'fhirType'     => 'Signature',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Signature',
                    'jsonKey'      => 'fixedSignature',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Timing',
                    'jsonKey'      => 'fixedTiming',
                ],
                [
                    'fhirType'     => 'ContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactDetail',
                    'jsonKey'      => 'fixedContactDetail',
                ],
                [
                    'fhirType'     => 'DataRequirement',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\DataRequirement',
                    'jsonKey'      => 'fixedDataRequirement',
                ],
                [
                    'fhirType'     => 'Expression',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Expression',
                    'jsonKey'      => 'fixedExpression',
                ],
                [
                    'fhirType'     => 'ParameterDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ParameterDefinition',
                    'jsonKey'      => 'fixedParameterDefinition',
                ],
                [
                    'fhirType'     => 'RelatedArtifact',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\RelatedArtifact',
                    'jsonKey'      => 'fixedRelatedArtifact',
                ],
                [
                    'fhirType'     => 'TriggerDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\TriggerDefinition',
                    'jsonKey'      => 'fixedTriggerDefinition',
                ],
                [
                    'fhirType'     => 'UsageContext',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext',
                    'jsonKey'      => 'fixedUsageContext',
                ],
                [
                    'fhirType'     => 'Availability',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Availability',
                    'jsonKey'      => 'fixedAvailability',
                ],
                [
                    'fhirType'     => 'ExtendedContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail',
                    'jsonKey'      => 'fixedExtendedContactDetail',
                ],
                [
                    'fhirType'     => 'Dosage',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Dosage',
                    'jsonKey'      => 'fixedDosage',
                ],
                [
                    'fhirType'     => 'Meta',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta',
                    'jsonKey'      => 'fixedMeta',
                ],
            ],
        )]
        public Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|string|IdPrimitive|InstantPrimitive|int|Integer64Primitive|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|CodeableReference|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|RatioRange|Reference|SampledData|Signature|Timing|ContactDetail|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Availability|ExtendedContactDetail|Dosage|Meta|null $fixed = null,
        /** @var Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|string|IdPrimitive|InstantPrimitive|int|Integer64Primitive|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|CodeableReference|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|RatioRange|Reference|SampledData|Signature|Timing|ContactDetail|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Availability|ExtendedContactDetail|Dosage|Meta|null pattern Value must have at least these property values */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'base64Binary',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\Base64BinaryPrimitive',
                    'jsonKey'      => 'patternBase64Binary',
                ],
                ['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'patternBoolean'],
                [
                    'fhirType'     => 'canonical',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'patternCanonical',
                ],
                [
                    'fhirType'     => 'code',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive',
                    'jsonKey'      => 'patternCode',
                ],
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive',
                    'jsonKey'      => 'patternDate',
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'patternDateTime',
                ],
                ['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'string', 'jsonKey' => 'patternDecimal'],
                [
                    'fhirType'     => 'id',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\IdPrimitive',
                    'jsonKey'      => 'patternId',
                ],
                [
                    'fhirType'     => 'instant',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive',
                    'jsonKey'      => 'patternInstant',
                ],
                ['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'patternInteger'],
                [
                    'fhirType'     => 'integer64',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\Integer64Primitive',
                    'jsonKey'      => 'patternInteger64',
                ],
                [
                    'fhirType'     => 'markdown',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive',
                    'jsonKey'      => 'patternMarkdown',
                ],
                [
                    'fhirType'     => 'oid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\OidPrimitive',
                    'jsonKey'      => 'patternOid',
                ],
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'patternPositiveInt',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
                    'jsonKey'      => 'patternString',
                ],
                [
                    'fhirType'     => 'time',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\TimePrimitive',
                    'jsonKey'      => 'patternTime',
                ],
                [
                    'fhirType'     => 'unsignedInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive',
                    'jsonKey'      => 'patternUnsignedInt',
                ],
                [
                    'fhirType'     => 'uri',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive',
                    'jsonKey'      => 'patternUri',
                ],
                [
                    'fhirType'     => 'url',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive',
                    'jsonKey'      => 'patternUrl',
                ],
                [
                    'fhirType'     => 'uuid',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UuidPrimitive',
                    'jsonKey'      => 'patternUuid',
                ],
                [
                    'fhirType'     => 'Address',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Address',
                    'jsonKey'      => 'patternAddress',
                ],
                [
                    'fhirType'     => 'Age',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Age',
                    'jsonKey'      => 'patternAge',
                ],
                [
                    'fhirType'     => 'Annotation',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation',
                    'jsonKey'      => 'patternAnnotation',
                ],
                [
                    'fhirType'     => 'Attachment',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment',
                    'jsonKey'      => 'patternAttachment',
                ],
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
                    'jsonKey'      => 'patternCodeableConcept',
                ],
                [
                    'fhirType'     => 'CodeableReference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference',
                    'jsonKey'      => 'patternCodeableReference',
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
                    'jsonKey'      => 'patternCoding',
                ],
                [
                    'fhirType'     => 'ContactPoint',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint',
                    'jsonKey'      => 'patternContactPoint',
                ],
                [
                    'fhirType'     => 'Count',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Count',
                    'jsonKey'      => 'patternCount',
                ],
                [
                    'fhirType'     => 'Distance',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Distance',
                    'jsonKey'      => 'patternDistance',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration',
                    'jsonKey'      => 'patternDuration',
                ],
                [
                    'fhirType'     => 'HumanName',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\HumanName',
                    'jsonKey'      => 'patternHumanName',
                ],
                [
                    'fhirType'     => 'Identifier',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
                    'jsonKey'      => 'patternIdentifier',
                ],
                [
                    'fhirType'     => 'Money',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Money',
                    'jsonKey'      => 'patternMoney',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
                    'jsonKey'      => 'patternPeriod',
                ],
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity',
                    'jsonKey'      => 'patternQuantity',
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Range',
                    'jsonKey'      => 'patternRange',
                ],
                [
                    'fhirType'     => 'Ratio',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Ratio',
                    'jsonKey'      => 'patternRatio',
                ],
                [
                    'fhirType'     => 'RatioRange',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\RatioRange',
                    'jsonKey'      => 'patternRatioRange',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
                    'jsonKey'      => 'patternReference',
                ],
                [
                    'fhirType'     => 'SampledData',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\SampledData',
                    'jsonKey'      => 'patternSampledData',
                ],
                [
                    'fhirType'     => 'Signature',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Signature',
                    'jsonKey'      => 'patternSignature',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Timing',
                    'jsonKey'      => 'patternTiming',
                ],
                [
                    'fhirType'     => 'ContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactDetail',
                    'jsonKey'      => 'patternContactDetail',
                ],
                [
                    'fhirType'     => 'DataRequirement',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\DataRequirement',
                    'jsonKey'      => 'patternDataRequirement',
                ],
                [
                    'fhirType'     => 'Expression',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Expression',
                    'jsonKey'      => 'patternExpression',
                ],
                [
                    'fhirType'     => 'ParameterDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ParameterDefinition',
                    'jsonKey'      => 'patternParameterDefinition',
                ],
                [
                    'fhirType'     => 'RelatedArtifact',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\RelatedArtifact',
                    'jsonKey'      => 'patternRelatedArtifact',
                ],
                [
                    'fhirType'     => 'TriggerDefinition',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\TriggerDefinition',
                    'jsonKey'      => 'patternTriggerDefinition',
                ],
                [
                    'fhirType'     => 'UsageContext',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext',
                    'jsonKey'      => 'patternUsageContext',
                ],
                [
                    'fhirType'     => 'Availability',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Availability',
                    'jsonKey'      => 'patternAvailability',
                ],
                [
                    'fhirType'     => 'ExtendedContactDetail',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail',
                    'jsonKey'      => 'patternExtendedContactDetail',
                ],
                [
                    'fhirType'     => 'Dosage',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Dosage',
                    'jsonKey'      => 'patternDosage',
                ],
                [
                    'fhirType'     => 'Meta',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta',
                    'jsonKey'      => 'patternMeta',
                ],
            ],
        )]
        public Base64BinaryPrimitive|bool|CanonicalPrimitive|CodePrimitive|DatePrimitive|DateTimePrimitive|string|IdPrimitive|InstantPrimitive|int|Integer64Primitive|MarkdownPrimitive|OidPrimitive|PositiveIntPrimitive|StringPrimitive|TimePrimitive|UnsignedIntPrimitive|UriPrimitive|UrlPrimitive|UuidPrimitive|Address|Age|Annotation|Attachment|CodeableConcept|CodeableReference|Coding|ContactPoint|Count|Distance|Duration|HumanName|Identifier|Money|Period|Quantity|Range|Ratio|RatioRange|Reference|SampledData|Signature|Timing|ContactDetail|DataRequirement|Expression|ParameterDefinition|RelatedArtifact|TriggerDefinition|UsageContext|Availability|ExtendedContactDetail|Dosage|Meta|null $pattern = null,
        /** @var array<ElementDefinitionExample> example Example value (as defined for type) */
        #[FhirProperty(
            fhirType: 'Element',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ElementDefinitionExample',
        )]
        public array $example = [],
        /** @var DatePrimitive|DateTimePrimitive|InstantPrimitive|TimePrimitive|string|int|Integer64Primitive|PositiveIntPrimitive|UnsignedIntPrimitive|Quantity|null minValue Minimum Allowed Value (for some types) */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive',
                    'jsonKey'      => 'minValueDate',
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'minValueDateTime',
                ],
                [
                    'fhirType'     => 'instant',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive',
                    'jsonKey'      => 'minValueInstant',
                ],
                [
                    'fhirType'     => 'time',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\TimePrimitive',
                    'jsonKey'      => 'minValueTime',
                ],
                ['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'string', 'jsonKey' => 'minValueDecimal'],
                ['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'minValueInteger'],
                [
                    'fhirType'     => 'integer64',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\Integer64Primitive',
                    'jsonKey'      => 'minValueInteger64',
                ],
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'minValuePositiveInt',
                ],
                [
                    'fhirType'     => 'unsignedInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive',
                    'jsonKey'      => 'minValueUnsignedInt',
                ],
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity',
                    'jsonKey'      => 'minValueQuantity',
                ],
            ],
        )]
        public DatePrimitive|DateTimePrimitive|InstantPrimitive|TimePrimitive|string|int|Integer64Primitive|PositiveIntPrimitive|UnsignedIntPrimitive|Quantity|null $minValue = null,
        /** @var DatePrimitive|DateTimePrimitive|InstantPrimitive|TimePrimitive|string|int|Integer64Primitive|PositiveIntPrimitive|UnsignedIntPrimitive|Quantity|null maxValue Maximum Allowed Value (for some types) */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive',
                    'jsonKey'      => 'maxValueDate',
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'maxValueDateTime',
                ],
                [
                    'fhirType'     => 'instant',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive',
                    'jsonKey'      => 'maxValueInstant',
                ],
                [
                    'fhirType'     => 'time',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\TimePrimitive',
                    'jsonKey'      => 'maxValueTime',
                ],
                ['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'string', 'jsonKey' => 'maxValueDecimal'],
                ['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'maxValueInteger'],
                [
                    'fhirType'     => 'integer64',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\Integer64Primitive',
                    'jsonKey'      => 'maxValueInteger64',
                ],
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'maxValuePositiveInt',
                ],
                [
                    'fhirType'     => 'unsignedInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive',
                    'jsonKey'      => 'maxValueUnsignedInt',
                ],
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity',
                    'jsonKey'      => 'maxValueQuantity',
                ],
            ],
        )]
        public DatePrimitive|DateTimePrimitive|InstantPrimitive|TimePrimitive|string|int|Integer64Primitive|PositiveIntPrimitive|UnsignedIntPrimitive|Quantity|null $maxValue = null,
        /** @var int|null maxLength Max length for string type data */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $maxLength = null,
        /** @var array<IdPrimitive> condition Reference to invariant about presence */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive', isArray: true)]
        public array $condition = [],
        /** @var array<ElementDefinitionConstraint> constraint Condition that must evaluate to true */
        #[FhirProperty(
            fhirType: 'Element',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ElementDefinitionConstraint',
        )]
        public array $constraint = [],
        /** @var bool|null mustHaveValue For primitives, that a value must be present - not replaced by an extension */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $mustHaveValue = null,
        /** @var array<CanonicalPrimitive> valueAlternatives Extensions that are allowed to replace a primitive value */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        public array $valueAlternatives = [],
        /** @var bool|null mustSupport If the element must be supported (discouraged - see obligations) */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $mustSupport = null,
        /** @var bool|null isModifier If this modifies the meaning of other elements */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $isModifier = null,
        /** @var StringPrimitive|string|null isModifierReason Reason that this element is marked as a modifier */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $isModifierReason = null,
        /** @var bool|null isSummary Include when _summary = true? */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $isSummary = null,
        /** @var ElementDefinitionBinding|null binding ValueSet details if this is coded */
        #[FhirProperty(fhirType: 'Element', propertyKind: 'complex')]
        public ?ElementDefinitionBinding $binding = null,
        /** @var array<ElementDefinitionMapping> mapping Map element to another set of definitions */
        #[FhirProperty(
            fhirType: 'Element',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ElementDefinitionMapping',
        )]
        public array $mapping = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
