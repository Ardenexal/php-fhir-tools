<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A goal describes an expected outcome that activities within the plan are intended to achieve. For example, weight loss, restoring an activity of daily living, obtaining herd immunity via immunization, meeting a process improvement objective, meeting the acceptance criteria for a test as specified by a quality specification, etc.
 */
#[FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.goal', fhirVersion: 'R5')]
class FHIRPlanDefinitionGoal extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null category E.g. Treatment, dietary, behavioral */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null description Code or text describing the goal */
        #[NotBlank]
        public ?FHIRCodeableConcept $description = null,
        /** @var FHIRCodeableConcept|null priority high-priority | medium-priority | low-priority */
        public ?FHIRCodeableConcept $priority = null,
        /** @var FHIRCodeableConcept|null start When goal pursuit begins */
        public ?FHIRCodeableConcept $start = null,
        /** @var array<FHIRCodeableConcept> addresses What does the goal address */
        public array $addresses = [],
        /** @var array<FHIRRelatedArtifact> documentation Supporting documentation for the goal */
        public array $documentation = [],
        /** @var array<FHIRPlanDefinitionGoalTarget> target Target outcome for the goal */
        public array $target = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
