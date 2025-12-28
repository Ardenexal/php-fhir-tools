<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Target or actual group of participants enrolled in study.
 */
#[FHIRBackboneElement(parentResource: 'ResearchStudy', elementPath: 'ResearchStudy.recruitment', fhirVersion: 'R5')]
class FHIRResearchStudyRecruitment extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUnsignedInt|null targetNumber Estimated total number of participants to be enrolled */
        public ?\FHIRUnsignedInt $targetNumber = null,
        /** @var FHIRUnsignedInt|null actualNumber Actual total number of participants enrolled in study */
        public ?\FHIRUnsignedInt $actualNumber = null,
        /** @var FHIRReference|null eligibility Inclusion and exclusion criteria */
        public ?\FHIRReference $eligibility = null,
        /** @var FHIRReference|null actualGroup Group of participants who were enrolled in study */
        public ?\FHIRReference $actualGroup = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
