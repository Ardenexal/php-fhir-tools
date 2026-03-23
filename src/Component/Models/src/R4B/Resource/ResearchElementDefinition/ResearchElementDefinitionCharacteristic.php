<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\ResearchElementDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\DataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\GroupMeasureType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A characteristic that defines the members of the research element. Multiple characteristics are applied with "and" semantics.
 */
#[FHIRBackboneElement(parentResource: 'ResearchElementDefinition', elementPath: 'ResearchElementDefinition.characteristic', fhirVersion: 'R4B')]
class ResearchElementDefinitionCharacteristic extends BackboneElement
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
        /** @var CodeableConcept|CanonicalPrimitive|Expression|DataRequirement|null definition What code or expression defines members? */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isRequired: true,
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
                    'jsonKey'      => 'definitionCodeableConcept',
                ],
                [
                    'fhirType'     => 'canonical',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'definitionCanonical',
                ],
                [
                    'fhirType'     => 'Expression',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Expression',
                    'jsonKey'      => 'definitionExpression',
                ],
                [
                    'fhirType'     => 'DataRequirement',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\DataRequirement',
                    'jsonKey'      => 'definitionDataRequirement',
                ],
            ],
        )]
        #[NotBlank]
        public CodeableConcept|CanonicalPrimitive|Expression|DataRequirement|null $definition = null,
        /** @var array<UsageContext> usageContext What code/value pairs define members? */
        #[FhirProperty(
            fhirType: 'UsageContext',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\UsageContext',
        )]
        public array $usageContext = [],
        /** @var bool|null exclude Whether the characteristic includes or excludes members */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $exclude = null,
        /** @var CodeableConcept|null unitOfMeasure What unit is the outcome described in? */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $unitOfMeasure = null,
        /** @var StringPrimitive|string|null studyEffectiveDescription What time period does the study cover */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $studyEffectiveDescription = null,
        /** @var DateTimePrimitive|Period|Duration|Timing|null studyEffective What time period does the study cover */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'studyEffectiveDateTime',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
                    'jsonKey'      => 'studyEffectivePeriod',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration',
                    'jsonKey'      => 'studyEffectiveDuration',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Timing',
                    'jsonKey'      => 'studyEffectiveTiming',
                ],
            ],
        )]
        public DateTimePrimitive|Period|Duration|Timing|null $studyEffective = null,
        /** @var Duration|null studyEffectiveTimeFromStart Observation time from study start */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $studyEffectiveTimeFromStart = null,
        /** @var GroupMeasureType|null studyEffectiveGroupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?GroupMeasureType $studyEffectiveGroupMeasure = null,
        /** @var StringPrimitive|string|null participantEffectiveDescription What time period do participants cover */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $participantEffectiveDescription = null,
        /** @var DateTimePrimitive|Period|Duration|Timing|null participantEffective What time period do participants cover */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'participantEffectiveDateTime',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
                    'jsonKey'      => 'participantEffectivePeriod',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration',
                    'jsonKey'      => 'participantEffectiveDuration',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Timing',
                    'jsonKey'      => 'participantEffectiveTiming',
                ],
            ],
        )]
        public DateTimePrimitive|Period|Duration|Timing|null $participantEffective = null,
        /** @var Duration|null participantEffectiveTimeFromStart Observation time from study start */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $participantEffectiveTimeFromStart = null,
        /** @var GroupMeasureType|null participantEffectiveGroupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?GroupMeasureType $participantEffectiveGroupMeasure = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
