<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The list of diagnosis relevant to this encounter.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.diagnosis', fhirVersion: 'R4')]
class FHIREncounterDiagnosis extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null condition The diagnosis or procedure relevant to the encounter */
        #[NotBlank]
        public ?FHIRReference $condition = null,
        /** @var FHIRCodeableConcept|null use Role that this diagnosis has within the encounter (e.g. admission, billing, discharge …) */
        public ?FHIRCodeableConcept $use = null,
        /** @var FHIRPositiveInt|null rank Ranking of the diagnosis (for each role type) */
        public ?FHIRPositiveInt $rank = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
