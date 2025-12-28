<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The actions, if any, produced by the evaluation of the artifact.
 */
#[FHIRBackboneElement(parentResource: 'RequestGroup', elementPath: 'RequestGroup.action', fhirVersion: 'R4')]
class FHIRRequestGroupAction extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null prefix User-visible prefix for the action (e.g. 1. or A.) */
        public \FHIRString|string|null $prefix = null,
        /** @var FHIRString|string|null title User-visible title */
        public \FHIRString|string|null $title = null,
        /** @var FHIRString|string|null description Short description of the action */
        public \FHIRString|string|null $description = null,
        /** @var FHIRString|string|null textEquivalent Static text equivalent of the action, used if the dynamic aspects cannot be interpreted by the receiving system */
        public \FHIRString|string|null $textEquivalent = null,
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?\FHIRRequestPriorityType $priority = null,
        /** @var array<FHIRCodeableConcept> code Code representing the meaning of the action or sub-actions */
        public array $code = [],
        /** @var array<FHIRRelatedArtifact> documentation Supporting documentation for the intended performer of the action */
        public array $documentation = [],
        /** @var array<FHIRRequestGroupActionCondition> condition Whether or not the action is applicable */
        public array $condition = [],
        /** @var array<FHIRRequestGroupActionRelatedAction> relatedAction Relationship to another action */
        public array $relatedAction = [],
        /** @var FHIRDateTime|FHIRAge|FHIRPeriod|FHIRDuration|FHIRRange|FHIRTiming|null timingX When the action should take place */
        public \FHIRDateTime|\FHIRAge|\FHIRPeriod|\FHIRDuration|\FHIRRange|\FHIRTiming|null $timingX = null,
        /** @var array<FHIRReference> participant Who should perform the action */
        public array $participant = [],
        /** @var FHIRCodeableConcept|null type create | update | remove | fire-event */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRActionGroupingBehaviorType|null groupingBehavior visual-group | logical-group | sentence-group */
        public ?\FHIRActionGroupingBehaviorType $groupingBehavior = null,
        /** @var FHIRActionSelectionBehaviorType|null selectionBehavior any | all | all-or-none | exactly-one | at-most-one | one-or-more */
        public ?\FHIRActionSelectionBehaviorType $selectionBehavior = null,
        /** @var FHIRActionRequiredBehaviorType|null requiredBehavior must | could | must-unless-documented */
        public ?\FHIRActionRequiredBehaviorType $requiredBehavior = null,
        /** @var FHIRActionPrecheckBehaviorType|null precheckBehavior yes | no */
        public ?\FHIRActionPrecheckBehaviorType $precheckBehavior = null,
        /** @var FHIRActionCardinalityBehaviorType|null cardinalityBehavior single | multiple */
        public ?\FHIRActionCardinalityBehaviorType $cardinalityBehavior = null,
        /** @var FHIRReference|null resource The target of the action */
        public ?\FHIRReference $resource = null,
        /** @var array<FHIRRequestGroupAction> action Sub action */
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
