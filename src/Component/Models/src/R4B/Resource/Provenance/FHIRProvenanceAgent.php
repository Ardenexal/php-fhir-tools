<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An actor taking a role in an activity  for which it can be assigned some degree of responsibility for the activity taking place.
 */
#[FHIRBackboneElement(parentResource: 'Provenance', elementPath: 'Provenance.agent', fhirVersion: 'R4B')]
class FHIRProvenanceAgent extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
        /** @var FHIRReference|null who Who participated */
        #[NotBlank]
        public ?FHIRReference $who = null,
        /** @var FHIRReference|null onBehalfOf Who the agent is representing */
        public ?FHIRReference $onBehalfOf = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
