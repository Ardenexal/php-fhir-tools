<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description An "outcome measure", "endpoint", "effect measure" or "measure of effect" is a specific measurement or observation used to quantify the effect of experimental variables on the participants in a study, or for observational studies, to describe patterns of diseases or traits or associations with exposures, risk factors or treatment.
 */
#[FHIRBackboneElement(parentResource: 'ResearchStudy', elementPath: 'ResearchStudy.outcomeMeasure', fhirVersion: 'R5')]
class FHIRResearchStudyOutcomeMeasure extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Label for the outcome */
        public \FHIRString|string|null $name = null,
        /** @var array<FHIRCodeableConcept> type primary | secondary | exploratory */
        public array $type = [],
        /** @var FHIRMarkdown|null description Description of the outcome */
        public ?\FHIRMarkdown $description = null,
        /** @var FHIRReference|null reference Structured outcome definition */
        public ?\FHIRReference $reference = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
