<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;

/**
 * @description Identifies all people and organizations who are expected to be involved in the care team.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CareTeam', elementPath: 'CareTeam.participant', fhirVersion: 'R5')]
class FHIRCareTeamParticipant extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null role Type of involvement */
        public ?FHIRCodeableConcept $role = null,
        /** @var FHIRReference|null member Who is involved */
        public ?FHIRReference $member = null,
        /** @var FHIRReference|null onBehalfOf Organization of the practitioner */
        public ?FHIRReference $onBehalfOf = null,
        /** @var FHIRPeriod|FHIRTiming|null coverageX When the member is generally available within this care team */
        public FHIRPeriod|FHIRTiming|null $coverageX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
