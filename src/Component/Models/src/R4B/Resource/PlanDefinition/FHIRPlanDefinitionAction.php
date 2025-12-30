<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRActionCardinalityBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRActionGroupingBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRActionPrecheckBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRActionRequiredBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRActionSelectionBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAge;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTriggerDefinition;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;

/**
 * @description An action or group of actions to be taken as part of the plan. For example, in clinical care, an action would be to prescribe a particular indicated medication, or perform a particular test as appropriate. In pharmaceutical quality, an action would be the test that needs to be performed on a drug product as defined in the quality specification.
 */
#[FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action', fhirVersion: 'R4B')]
class FHIRPlanDefinitionAction extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null prefix User-visible prefix for the action (e.g. 1. or A.) */
        public FHIRString|string|null $prefix = null,
        /** @var FHIRString|string|null title User-visible title */
        public FHIRString|string|null $title = null,
        /** @var FHIRString|string|null description Brief description of the action */
        public FHIRString|string|null $description = null,
        /** @var FHIRString|string|null textEquivalent Static text equivalent of the action, used if the dynamic aspects cannot be interpreted by the receiving system */
        public FHIRString|string|null $textEquivalent = null,
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var array<FHIRCodeableConcept> code Code representing the meaning of the action or sub-actions */
        public array $code = [],
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
        /** @var array<FHIRDataRequirement> input Input data requirements */
        public array $input = [],
        /** @var array<FHIRDataRequirement> output Output data definition */
        public array $output = [],
        /** @var array<FHIRPlanDefinitionActionRelatedAction> relatedAction Relationship to another action */
        public array $relatedAction = [],
        /** @var FHIRDateTime|FHIRAge|FHIRPeriod|FHIRDuration|FHIRRange|FHIRTiming|null timingX When the action should take place */
        public FHIRDateTime|FHIRAge|FHIRPeriod|FHIRDuration|FHIRRange|FHIRTiming|null $timingX = null,
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
