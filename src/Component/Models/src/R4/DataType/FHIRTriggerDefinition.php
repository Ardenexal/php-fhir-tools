<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTriggerTypeType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/TriggerDefinition
 *
 * @description A description of a triggering event. Triggering events can be named events, data events, or periodic, as determined by the type element.
 */
#[FHIRComplexType(typeName: 'TriggerDefinition', fhirVersion: 'R4')]
class FHIRTriggerDefinition extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRTriggerTypeType|null type named-event | periodic | data-changed | data-added | data-modified | data-removed | data-accessed | data-access-ended */
        #[NotBlank]
        public ?FHIRTriggerTypeType $type = null,
        /** @var FHIRString|string|null name Name or URI that identifies the event */
        public FHIRString|string|null $name = null,
        /** @var FHIRTiming|FHIRReference|FHIRDate|FHIRDateTime|null timingX Timing of the event */
        public FHIRTiming|FHIRReference|FHIRDate|FHIRDateTime|null $timingX = null,
        /** @var array<FHIRDataRequirement> data Triggering data of the event (multiple = 'and') */
        public array $data = [],
        /** @var FHIRExpression|null condition Whether the event triggers (boolean expression) */
        public ?FHIRExpression $condition = null,
    ) {
        parent::__construct($id, $extension);
    }
}
