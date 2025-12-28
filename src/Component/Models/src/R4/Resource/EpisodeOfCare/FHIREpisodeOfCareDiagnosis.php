<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The list of diagnosis relevant to this episode of care.
 */
#[FHIRBackboneElement(parentResource: 'EpisodeOfCare', elementPath: 'EpisodeOfCare.diagnosis', fhirVersion: 'R4')]
class FHIREpisodeOfCareDiagnosis extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null condition Conditions/problems/diagnoses this episode of care is for */
        #[NotBlank]
        public ?\FHIRReference $condition = null,
        /** @var FHIRCodeableConcept|null role Role that this diagnosis has within the episode of care (e.g. admission, billing, discharge …) */
        public ?\FHIRCodeableConcept $role = null,
        /** @var FHIRPositiveInt|null rank Ranking of the diagnosis (for each role type) */
        public ?\FHIRPositiveInt $rank = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
