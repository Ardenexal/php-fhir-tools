<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;

/**
 * @description The list of people responsible for providing the service.
 */
#[FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.participant', fhirVersion: 'R5')]
class FHIREncounterParticipant extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableConcept> type Role of participant in encounter */
        public array $type = [],
        /** @var FHIRPeriod|null period Period of time during the encounter that the participant participated */
        public ?FHIRPeriod $period = null,
        /** @var FHIRReference|null actor The individual, device, or service participating in the encounter */
        public ?FHIRReference $actor = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
