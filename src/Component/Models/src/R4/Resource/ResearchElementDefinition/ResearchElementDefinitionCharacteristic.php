<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ResearchElementDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\GroupMeasureType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A characteristic that defines the members of the research element. Multiple characteristics are applied with "and" semantics.
 */
#[FHIRBackboneElement(parentResource: 'ResearchElementDefinition', elementPath: 'ResearchElementDefinition.characteristic', fhirVersion: 'R4')]
class ResearchElementDefinitionCharacteristic extends BackboneElement
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
        'definitionX' => [
            'fhirType'     => 'choice',
            'propertyKind' => 'choice',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => true,
            'jsonKey'      => null,
            'variants'     => [
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
                    'jsonKey'      => 'definitionCodeableConcept',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'canonical',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'definitionCanonical',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Expression',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression',
                    'jsonKey'      => 'definitionExpression',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'DataRequirement',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement',
                    'jsonKey'      => 'definitionDataRequirement',
                    'isBuiltin'    => false,
                ],
            ],
        ],
        'usageContext' => [
            'fhirType'     => 'UsageContext',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'exclude' => [
            'fhirType'     => 'boolean',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'unitOfMeasure' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'studyEffectiveDescription' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'studyEffectiveX' => [
            'fhirType'     => 'choice',
            'propertyKind' => 'choice',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => true,
            'jsonKey'      => null,
            'variants'     => [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'studyEffectiveDateTime',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'studyEffectivePeriod',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration',
                    'jsonKey'      => 'studyEffectiveDuration',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing',
                    'jsonKey'      => 'studyEffectiveTiming',
                    'isBuiltin'    => false,
                ],
            ],
        ],
        'studyEffectiveTimeFromStart' => [
            'fhirType'     => 'Duration',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'studyEffectiveGroupMeasure' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'participantEffectiveDescription' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'participantEffectiveX' => [
            'fhirType'     => 'choice',
            'propertyKind' => 'choice',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => true,
            'jsonKey'      => null,
            'variants'     => [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'participantEffectiveDateTime',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'participantEffectivePeriod',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration',
                    'jsonKey'      => 'participantEffectiveDuration',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing',
                    'jsonKey'      => 'participantEffectiveTiming',
                    'isBuiltin'    => false,
                ],
            ],
        ],
        'participantEffectiveTimeFromStart' => [
            'fhirType'     => 'Duration',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'participantEffectiveGroupMeasure' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
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
        /** @var CodeableConcept|CanonicalPrimitive|Expression|DataRequirement|null definitionX What code or expression defines members? */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isRequired: true,
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
                    'jsonKey'      => 'definitionCodeableConcept',
                ],
                [
                    'fhirType'     => 'canonical',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'definitionCanonical',
                ],
                [
                    'fhirType'     => 'Expression',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression',
                    'jsonKey'      => 'definitionExpression',
                ],
                [
                    'fhirType'     => 'DataRequirement',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement',
                    'jsonKey'      => 'definitionDataRequirement',
                ],
            ],
        )]
        #[NotBlank]
        public CodeableConcept|CanonicalPrimitive|Expression|DataRequirement|null $definitionX = null,
        /** @var array<UsageContext> usageContext What code/value pairs define members? */
        #[FhirProperty(fhirType: 'UsageContext', propertyKind: 'complex', isArray: true)]
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
        /** @var DateTimePrimitive|Period|Duration|Timing|null studyEffectiveX What time period does the study cover */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'studyEffectiveDateTime',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'studyEffectivePeriod',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration',
                    'jsonKey'      => 'studyEffectiveDuration',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing',
                    'jsonKey'      => 'studyEffectiveTiming',
                ],
            ],
        )]
        public DateTimePrimitive|Period|Duration|Timing|null $studyEffectiveX = null,
        /** @var Duration|null studyEffectiveTimeFromStart Observation time from study start */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $studyEffectiveTimeFromStart = null,
        /** @var GroupMeasureType|null studyEffectiveGroupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?GroupMeasureType $studyEffectiveGroupMeasure = null,
        /** @var StringPrimitive|string|null participantEffectiveDescription What time period do participants cover */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $participantEffectiveDescription = null,
        /** @var DateTimePrimitive|Period|Duration|Timing|null participantEffectiveX What time period do participants cover */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'participantEffectiveDateTime',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'participantEffectivePeriod',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration',
                    'jsonKey'      => 'participantEffectiveDuration',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing',
                    'jsonKey'      => 'participantEffectiveTiming',
                ],
            ],
        )]
        public DateTimePrimitive|Period|Duration|Timing|null $participantEffectiveX = null,
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
