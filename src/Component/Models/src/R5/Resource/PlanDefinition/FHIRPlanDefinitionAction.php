<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAge;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTriggerDefinition;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @description An action or group of actions to be taken as part of the plan. For example, in clinical care, an action would be to prescribe a particular indicated medication, or perform a particular test as appropriate. In pharmaceutical quality, an action would be the test that needs to be performed on a drug product as defined in the quality specification.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action', fhirVersion: 'R5')]
class FHIRPlanDefinitionAction extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null linkId Unique id for the action in the PlanDefinition */
        public FHIRString|string|null $linkId = null,
        /** @var FHIRString|string|null prefix User-visible prefix for the action (e.g. 1. or A.) */
        public FHIRString|string|null $prefix = null,
        /** @var FHIRString|string|null title User-visible title */
        public FHIRString|string|null $title = null,
        /** @var FHIRMarkdown|null description Brief description of the action */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRMarkdown|null textEquivalent Static text equivalent of the action, used if the dynamic aspects cannot be interpreted by the receiving system */
        public ?FHIRMarkdown $textEquivalent = null,
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var FHIRCodeableConcept|null code Code representing the meaning of the action or sub-actions */
        public ?FHIRCodeableConcept $code = null,
        /** @var array<FHIRCodeableConcept> reason Why the action should be performed */
        public array $reason = [],
        /** @var array<FHIRRelatedArtifact> documentation Supporting documentation for the intended performer of the action */
        public array $documentation = [],
        /** @var array<FHIRId> goalId What goals this action supports */
        public array $goalId = [],
        /** @var FHIRCodeableConcept|FHIRReference|FHIRCanonical|null subjectX Type of individual the action is focused on */
        public FHIRCodeableConcept|FHIRReference|FHIRCanonical|null $subjectX = null,
        /** @var array<FHIRTriggerDefinition> trigger When the action should be triggered */
        public array $trigger = [],
        /** @var array<FHIRPlanDefinitionActionCondition> condition Whether or not the action is applicable */
        public array $condition = [],
        /** @var array<FHIRPlanDefinitionActionInput> input Input data requirements */
        public array $input = [],
        /** @var array<FHIRPlanDefinitionActionOutput> output Output data definition */
        public array $output = [],
        /** @var array<FHIRPlanDefinitionActionRelatedAction> relatedAction Relationship to another action */
        public array $relatedAction = [],
        /** @var FHIRAge|FHIRDuration|FHIRRange|FHIRTiming|null timingX When the action should take place */
        public FHIRAge|FHIRDuration|FHIRRange|FHIRTiming|null $timingX = null,
        /** @var FHIRCodeableReference|null location Where it should happen */
        public ?FHIRCodeableReference $location = null,
        /** @var array<FHIRPlanDefinitionActionParticipant> participant Who should participate in the action */
        public array $participant = [],
        /** @var FHIRCodeableConcept|null type create | update | remove | fire-event */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRActionGroupingBehaviorType|null groupingBehavior visual-group | logical-group | sentence-group */
        public ?FHIRActionGroupingBehaviorType $groupingBehavior = null,
        /** @var FHIRActionSelectionBehaviorType|null selectionBehavior any | all | all-or-none | exactly-one | at-most-one | one-or-more */
        public ?FHIRActionSelectionBehaviorType $selectionBehavior = null,
        /** @var FHIRActionRequiredBehaviorType|null requiredBehavior must | could | must-unless-documented */
        public ?FHIRActionRequiredBehaviorType $requiredBehavior = null,
        /** @var FHIRActionPrecheckBehaviorType|null precheckBehavior yes | no */
        public ?FHIRActionPrecheckBehaviorType $precheckBehavior = null,
        /** @var FHIRActionCardinalityBehaviorType|null cardinalityBehavior single | multiple */
        public ?FHIRActionCardinalityBehaviorType $cardinalityBehavior = null,
        /** @var FHIRCanonical|FHIRUri|null definitionX Description of the activity to be performed */
        public FHIRCanonical|FHIRUri|null $definitionX = null,
        /** @var FHIRCanonical|null transform Transform to apply the template */
        public ?FHIRCanonical $transform = null,
        /** @var array<FHIRPlanDefinitionActionDynamicValue> dynamicValue Dynamic aspects of the definition */
        public array $dynamicValue = [],
        /** @var array<FHIRPlanDefinitionAction> action A sub-action */
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
