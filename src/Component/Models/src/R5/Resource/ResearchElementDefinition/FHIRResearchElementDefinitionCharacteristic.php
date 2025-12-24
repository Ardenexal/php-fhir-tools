<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDataRequirement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A characteristic that defines the members of the research element. Multiple characteristics are applied with "and" semantics.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ResearchElementDefinition', elementPath: 'ResearchElementDefinition.characteristic', fhirVersion: 'R5')]
class FHIRResearchElementDefinitionCharacteristic extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|FHIRCanonical|FHIRExpression|FHIRDataRequirement|null definitionX What code or expression defines members? */
        #[NotBlank]
        public FHIRCodeableConcept|FHIRCanonical|FHIRExpression|FHIRDataRequirement|null $definitionX = null,
        /** @var array<FHIRUsageContext> usageContext What code/value pairs define members? */
        public array $usageContext = [],
        /** @var FHIRBoolean|null exclude Whether the characteristic includes or excludes members */
        public ?FHIRBoolean $exclude = null,
        /** @var FHIRCodeableConcept|null unitOfMeasure What unit is the outcome described in? */
        public ?FHIRCodeableConcept $unitOfMeasure = null,
        /** @var FHIRString|string|null studyEffectiveDescription What time period does the study cover */
        public FHIRString|string|null $studyEffectiveDescription = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRDuration|FHIRTiming|null studyEffectiveX What time period does the study cover */
        public FHIRDateTime|FHIRPeriod|FHIRDuration|FHIRTiming|null $studyEffectiveX = null,
        /** @var FHIRDuration|null studyEffectiveTimeFromStart Observation time from study start */
        public ?FHIRDuration $studyEffectiveTimeFromStart = null,
        /** @var FHIRGroupMeasureType|null studyEffectiveGroupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
        public ?FHIRGroupMeasureType $studyEffectiveGroupMeasure = null,
        /** @var FHIRString|string|null participantEffectiveDescription What time period do participants cover */
        public FHIRString|string|null $participantEffectiveDescription = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRDuration|FHIRTiming|null participantEffectiveX What time period do participants cover */
        public FHIRDateTime|FHIRPeriod|FHIRDuration|FHIRTiming|null $participantEffectiveX = null,
        /** @var FHIRDuration|null participantEffectiveTimeFromStart Observation time from study start */
        public ?FHIRDuration $participantEffectiveTimeFromStart = null,
        /** @var FHIRGroupMeasureType|null participantEffectiveGroupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
        public ?FHIRGroupMeasureType $participantEffectiveGroupMeasure = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
