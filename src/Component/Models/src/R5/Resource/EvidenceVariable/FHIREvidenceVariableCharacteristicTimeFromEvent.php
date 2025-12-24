<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;

/**
 * @description Timing in which the characteristic is determined.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EvidenceVariable', elementPath: 'EvidenceVariable.characteristic.timeFromEvent', fhirVersion: 'R5')]
class FHIREvidenceVariableCharacteristicTimeFromEvent extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRMarkdown|null description Human readable description */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRAnnotation> note Used for footnotes or explanatory notes */
        public array $note = [],
        /** @var FHIRCodeableConcept|FHIRReference|FHIRDateTime|FHIRId|null eventX The event used as a base point (reference point) in time */
        public FHIRCodeableConcept|FHIRReference|FHIRDateTime|FHIRId|null $eventX = null,
        /** @var FHIRQuantity|null quantity Used to express the observation at a defined amount of time before or after the event */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRRange|null range Used to express the observation within a period before and/or after the event */
        public ?FHIRRange $range = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
