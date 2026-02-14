<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CareTeam;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description Identifies all people and organizations who are expected to be involved in the care team.
 */
#[FHIRBackboneElement(parentResource: 'CareTeam', elementPath: 'CareTeam.participant', fhirVersion: 'R4')]
class CareTeamParticipant extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> role Type of involvement */
        public array $role = [],
        /** @var Reference|null member Who is involved */
        public ?Reference $member = null,
        /** @var Reference|null onBehalfOf Organization of the practitioner */
        public ?Reference $onBehalfOf = null,
        /** @var Period|null period Time period of participant */
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
