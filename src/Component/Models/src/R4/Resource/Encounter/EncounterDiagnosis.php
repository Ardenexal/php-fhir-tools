<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The list of diagnosis relevant to this encounter.
 */
#[FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.diagnosis', fhirVersion: 'R4')]
class EncounterDiagnosis extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null condition The diagnosis or procedure relevant to the encounter */
        #[NotBlank]
        public ?Reference $condition = null,
        /** @var CodeableConcept|null use Role that this diagnosis has within the encounter (e.g. admission, billing, discharge …) */
        public ?CodeableConcept $use = null,
        /** @var PositiveIntPrimitive|null rank Ranking of the diagnosis (for each role type) */
        public ?PositiveIntPrimitive $rank = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
