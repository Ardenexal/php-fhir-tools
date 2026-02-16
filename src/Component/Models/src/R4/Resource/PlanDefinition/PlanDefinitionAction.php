<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionCardinalityBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionGroupingBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionPrecheckBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionRequiredBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionSelectionBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TriggerDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @description An action or group of actions to be taken as part of the plan.
 */
#[FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action', fhirVersion: 'R4')]
class PlanDefinitionAction extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null prefix User-visible prefix for the action (e.g. 1. or A.) */
        public StringPrimitive|string|null $prefix = null,
        /** @var StringPrimitive|string|null title User-visible title */
        public StringPrimitive|string|null $title = null,
        /** @var StringPrimitive|string|null description Brief description of the action */
        public StringPrimitive|string|null $description = null,
        /** @var StringPrimitive|string|null textEquivalent Static text equivalent of the action, used if the dynamic aspects cannot be interpreted by the receiving system */
        public StringPrimitive|string|null $textEquivalent = null,
        /** @var RequestPriorityType|null priority routine | urgent | asap | stat */
        public ?RequestPriorityType $priority = null,
        /** @var array<CodeableConcept> code Code representing the meaning of the action or sub-actions */
        public array $code = [],
        /** @var array<CodeableConcept> reason Why the action should be performed */
        public array $reason = [],
        /** @var array<RelatedArtifact> documentation Supporting documentation for the intended performer of the action */
        public array $documentation = [],
        /** @var array<IdPrimitive> goalId What goals this action supports */
        public array $goalId = [],
        /** @var CodeableConcept|Reference|null subjectX Type of individual the action is focused on */
        public CodeableConcept|Reference|null $subjectX = null,
        /** @var array<TriggerDefinition> trigger When the action should be triggered */
        public array $trigger = [],
        /** @var array<PlanDefinitionActionCondition> condition Whether or not the action is applicable */
        public array $condition = [],
        /** @var array<DataRequirement> input Input data requirements */
        public array $input = [],
        /** @var array<DataRequirement> output Output data definition */
        public array $output = [],
        /** @var array<PlanDefinitionActionRelatedAction> relatedAction Relationship to another action */
        public array $relatedAction = [],
        /** @var DateTimePrimitive|Age|Period|Duration|Range|Timing|null timingX When the action should take place */
        public DateTimePrimitive|Age|Period|Duration|Range|Timing|null $timingX = null,
        /** @var array<PlanDefinitionActionParticipant> participant Who should participate in the action */
        public array $participant = [],
        /** @var CodeableConcept|null type create | update | remove | fire-event */
        public ?CodeableConcept $type = null,
        /** @var ActionGroupingBehaviorType|null groupingBehavior visual-group | logical-group | sentence-group */
        public ?ActionGroupingBehaviorType $groupingBehavior = null,
        /** @var ActionSelectionBehaviorType|null selectionBehavior any | all | all-or-none | exactly-one | at-most-one | one-or-more */
        public ?ActionSelectionBehaviorType $selectionBehavior = null,
        /** @var ActionRequiredBehaviorType|null requiredBehavior must | could | must-unless-documented */
        public ?ActionRequiredBehaviorType $requiredBehavior = null,
        /** @var ActionPrecheckBehaviorType|null precheckBehavior yes | no */
        public ?ActionPrecheckBehaviorType $precheckBehavior = null,
        /** @var ActionCardinalityBehaviorType|null cardinalityBehavior single | multiple */
        public ?ActionCardinalityBehaviorType $cardinalityBehavior = null,
        /** @var CanonicalPrimitive|UriPrimitive|null definitionX Description of the activity to be performed */
        public CanonicalPrimitive|UriPrimitive|null $definitionX = null,
        /** @var CanonicalPrimitive|null transform Transform to apply the template */
        public ?CanonicalPrimitive $transform = null,
        /** @var array<PlanDefinitionActionDynamicValue> dynamicValue Dynamic aspects of the definition */
        public array $dynamicValue = [],
        /** @var array<PlanDefinitionAction> action A sub-action */
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
