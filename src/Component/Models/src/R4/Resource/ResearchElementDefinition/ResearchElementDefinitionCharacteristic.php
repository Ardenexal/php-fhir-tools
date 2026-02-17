<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ResearchElementDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
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
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|CanonicalPrimitive|Expression|DataRequirement|null definitionX What code or expression defines members? */
        #[NotBlank]
        public CodeableConcept|CanonicalPrimitive|Expression|DataRequirement|null $definitionX = null,
        /** @var array<UsageContext> usageContext What code/value pairs define members? */
        public array $usageContext = [],
        /** @var bool|null exclude Whether the characteristic includes or excludes members */
        public ?bool $exclude = null,
        /** @var CodeableConcept|null unitOfMeasure What unit is the outcome described in? */
        public ?CodeableConcept $unitOfMeasure = null,
        /** @var StringPrimitive|string|null studyEffectiveDescription What time period does the study cover */
        public StringPrimitive|string|null $studyEffectiveDescription = null,
        /** @var DateTimePrimitive|Period|Duration|Timing|null studyEffectiveX What time period does the study cover */
        public DateTimePrimitive|Period|Duration|Timing|null $studyEffectiveX = null,
        /** @var Duration|null studyEffectiveTimeFromStart Observation time from study start */
        public ?Duration $studyEffectiveTimeFromStart = null,
        /** @var GroupMeasureType|null studyEffectiveGroupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
        public ?GroupMeasureType $studyEffectiveGroupMeasure = null,
        /** @var StringPrimitive|string|null participantEffectiveDescription What time period do participants cover */
        public StringPrimitive|string|null $participantEffectiveDescription = null,
        /** @var DateTimePrimitive|Period|Duration|Timing|null participantEffectiveX What time period do participants cover */
        public DateTimePrimitive|Period|Duration|Timing|null $participantEffectiveX = null,
        /** @var Duration|null participantEffectiveTimeFromStart Observation time from study start */
        public ?Duration $participantEffectiveTimeFromStart = null,
        /** @var GroupMeasureType|null participantEffectiveGroupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
        public ?GroupMeasureType $participantEffectiveGroupMeasure = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
