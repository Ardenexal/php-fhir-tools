<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The list of medical reasons that are expected to be addressed during the episode of care.
 */
#[FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.reason', fhirVersion: 'R5')]
class FHIREncounterReason extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableConcept> use What the reason value should be used for/as */
        public array $use = [],
        /** @var array<FHIRCodeableReference> value Reason the encounter takes place (core or reference) */
        public array $value = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
