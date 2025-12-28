<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The list of medical conditions that were addressed during the episode of care.
 */
#[FHIRBackboneElement(parentResource: 'EpisodeOfCare', elementPath: 'EpisodeOfCare.diagnosis', fhirVersion: 'R5')]
class FHIREpisodeOfCareDiagnosis extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableReference> condition The medical condition that was addressed during the episode of care */
        public array $condition = [],
        /** @var FHIRCodeableConcept|null use Role that this diagnosis has within the episode of care (e.g. admission, billing, discharge …) */
        public ?\FHIRCodeableConcept $use = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
