<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRActionCardinalityBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRActionGroupingBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRActionPrecheckBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRActionRequiredBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRActionSelectionBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAge;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @description The actions, if any, produced by the evaluation of the artifact.
 */
#[FHIRBackboneElement(parentResource: 'RequestOrchestration', elementPath: 'RequestOrchestration.action', fhirVersion: 'R5')]
class FHIRRequestOrchestrationAction extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null linkId Pointer to specific item from the PlanDefinition */
        public FHIRString|string|null $linkId = null,
        /** @var FHIRString|string|null prefix User-visible prefix for the action (e.g. 1. or A.) */
        public FHIRString|string|null $prefix = null,
        /** @var FHIRString|string|null title User-visible title */
        public FHIRString|string|null $title = null,
        /** @var FHIRMarkdown|null description Short description of the action */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRMarkdown|null textEquivalent Static text equivalent of the action, used if the dynamic aspects cannot be interpreted by the receiving system */
        public ?FHIRMarkdown $textEquivalent = null,
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var array<FHIRCodeableConcept> code Code representing the meaning of the action or sub-actions */
        public array $code = [],
        /** @var array<FHIRRelatedArtifact> documentation Supporting documentation for the intended performer of the action */
        public array $documentation = [],
        /** @var array<FHIRReference> goal What goals */
        public array $goal = [],
        /** @var array<FHIRRequestOrchestrationActionCondition> condition Whether or not the action is applicable */
        public array $condition = [],
        /** @var array<FHIRRequestOrchestrationActionInput> input Input data requirements */
        public array $input = [],
        /** @var array<FHIRRequestOrchestrationActionOutput> output Output data definition */
        public array $output = [],
        /** @var array<FHIRRequestOrchestrationActionRelatedAction> relatedAction Relationship to another action */
        public array $relatedAction = [],
        /** @var FHIRDateTime|FHIRAge|FHIRPeriod|FHIRDuration|FHIRRange|FHIRTiming|null timingX When the action should take place */
        public FHIRDateTime|FHIRAge|FHIRPeriod|FHIRDuration|FHIRRange|FHIRTiming|null $timingX = null,
        /** @var FHIRCodeableReference|null location Where it should happen */
        public ?FHIRCodeableReference $location = null,
        /** @var array<FHIRRequestOrchestrationActionParticipant> participant Who should perform the action */
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
        /** @var FHIRReference|null resource The target of the action */
        public ?FHIRReference $resource = null,
        /** @var FHIRCanonical|FHIRUri|null definitionX Description of the activity to be performed */
        public FHIRCanonical|FHIRUri|null $definitionX = null,
        /** @var FHIRCanonical|null transform Transform to apply the template */
        public ?FHIRCanonical $transform = null,
        /** @var array<FHIRRequestOrchestrationActionDynamicValue> dynamicValue Dynamic aspects of the definition */
        public array $dynamicValue = [],
        /** @var array<FHIRRequestOrchestrationAction> action Sub action */
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
