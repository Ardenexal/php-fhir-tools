<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\EvidenceVariable;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\GroupMeasureType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TriggerDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A characteristic that defines the members of the evidence element. Multiple characteristics are applied with "and" semantics.
 */
#[FHIRBackboneElement(parentResource: 'EvidenceVariable', elementPath: 'EvidenceVariable.characteristic', fhirVersion: 'R4')]
class EvidenceVariableCharacteristic extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null description Natural language description of the characteristic */
        public StringPrimitive|string|null $description = null,
        /** @var Reference|CanonicalPrimitive|CodeableConcept|Expression|DataRequirement|TriggerDefinition|null definitionX What code or expression defines members? */
        #[NotBlank]
        public Reference|CanonicalPrimitive|CodeableConcept|Expression|DataRequirement|TriggerDefinition|null $definitionX = null,
        /** @var array<UsageContext> usageContext What code/value pairs define members? */
        public array $usageContext = [],
        /** @var bool|null exclude Whether the characteristic includes or excludes members */
        public ?bool $exclude = null,
        /** @var DateTimePrimitive|Period|Duration|Timing|null participantEffectiveX What time period do participants cover */
        public DateTimePrimitive|Period|Duration|Timing|null $participantEffectiveX = null,
        /** @var Duration|null timeFromStart Observation time from study start */
        public ?Duration $timeFromStart = null,
        /** @var GroupMeasureType|null groupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
        public ?GroupMeasureType $groupMeasure = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
