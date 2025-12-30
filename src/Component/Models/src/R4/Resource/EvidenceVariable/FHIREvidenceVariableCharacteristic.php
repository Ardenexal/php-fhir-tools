<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExpression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRGroupMeasureType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRTriggerDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A characteristic that defines the members of the evidence element. Multiple characteristics are applied with "and" semantics.
 */
#[FHIRBackboneElement(parentResource: 'EvidenceVariable', elementPath: 'EvidenceVariable.characteristic', fhirVersion: 'R4')]
class FHIREvidenceVariableCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Natural language description of the characteristic */
        public FHIRString|string|null $description = null,
        /** @var FHIRReference|FHIRCanonical|FHIRCodeableConcept|FHIRExpression|FHIRDataRequirement|FHIRTriggerDefinition|null definitionX What code or expression defines members? */
        #[NotBlank]
        public FHIRReference|FHIRCanonical|FHIRCodeableConcept|FHIRExpression|FHIRDataRequirement|FHIRTriggerDefinition|null $definitionX = null,
        /** @var array<FHIRUsageContext> usageContext What code/value pairs define members? */
        public array $usageContext = [],
        /** @var FHIRBoolean|null exclude Whether the characteristic includes or excludes members */
        public ?FHIRBoolean $exclude = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRDuration|FHIRTiming|null participantEffectiveX What time period do participants cover */
        public FHIRDateTime|FHIRPeriod|FHIRDuration|FHIRTiming|null $participantEffectiveX = null,
        /** @var FHIRDuration|null timeFromStart Observation time from study start */
        public ?FHIRDuration $timeFromStart = null,
        /** @var FHIRGroupMeasureType|null groupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
        public ?FHIRGroupMeasureType $groupMeasure = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
