<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The list of diagnosis relevant to this encounter.
 */
#[FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.diagnosis', fhirVersion: 'R4B')]
class FHIREncounterDiagnosis extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
