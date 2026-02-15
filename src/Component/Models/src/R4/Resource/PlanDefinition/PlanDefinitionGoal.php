<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Goals that describe what the activities within the plan are intended to achieve. For example, weight loss, restoring an activity of daily living, obtaining herd immunity via immunization, meeting a process improvement objective, etc.
 */
#[FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.goal', fhirVersion: 'R4')]
class PlanDefinitionGoal extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null category E.g. Treatment, dietary, behavioral */
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|null description Code or text describing the goal */
        #[NotBlank]
        public ?CodeableConcept $description = null,
        /** @var CodeableConcept|null priority high-priority | medium-priority | low-priority */
        public ?CodeableConcept $priority = null,
        /** @var CodeableConcept|null start When goal pursuit begins */
        public ?CodeableConcept $start = null,
        /** @var array<CodeableConcept> addresses What does the goal address */
        public array $addresses = [],
        /** @var array<RelatedArtifact> documentation Supporting documentation for the goal */
        public array $documentation = [],
        /** @var array<PlanDefinitionGoalTarget> target Target outcome for the goal */
        public array $target = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
