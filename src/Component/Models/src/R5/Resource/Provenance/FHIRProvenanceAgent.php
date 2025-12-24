<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An actor taking a role in an activity  for which it can be assigned some degree of responsibility for the activity taking place.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Provenance', elementPath: 'Provenance.agent', fhirVersion: 'R5')]
class FHIRProvenanceAgent extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type How the agent participated */
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> role What the agents role was */
        public array $role = [],
        /** @var FHIRReference|null who The agent that participated in the event */
        #[NotBlank]
        public ?FHIRReference $who = null,
        /** @var FHIRReference|null onBehalfOf The agent that delegated */
        public ?FHIRReference $onBehalfOf = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
