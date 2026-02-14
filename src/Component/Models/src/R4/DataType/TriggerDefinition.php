<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/TriggerDefinition
 *
 * @description A description of a triggering event. Triggering events can be named events, data events, or periodic, as determined by the type element.
 */
#[FHIRComplexType(typeName: 'TriggerDefinition', fhirVersion: 'R4')]
class TriggerDefinition extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var TriggerTypeType|null type named-event | periodic | data-changed | data-added | data-modified | data-removed | data-accessed | data-access-ended */
        #[NotBlank]
        public ?TriggerTypeType $type = null,
        /** @var StringPrimitive|string|null name Name or URI that identifies the event */
        public StringPrimitive|string|null $name = null,
        /** @var Timing|Reference|DatePrimitive|DateTimePrimitive|null timingX Timing of the event */
        public Timing|Reference|DatePrimitive|DateTimePrimitive|null $timingX = null,
        /** @var array<DataRequirement> data Triggering data of the event (multiple = 'and') */
        public array $data = [],
        /** @var Expression|null condition Whether the event triggers (boolean expression) */
        public ?Expression $condition = null,
    ) {
        parent::__construct($id, $extension);
    }
}
