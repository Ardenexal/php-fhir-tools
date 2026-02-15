<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RequestGroup;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionCardinalityBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionGroupingBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionPrecheckBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionRequiredBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionSelectionBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description The actions, if any, produced by the evaluation of the artifact.
 */
#[FHIRBackboneElement(parentResource: 'RequestGroup', elementPath: 'RequestGroup.action', fhirVersion: 'R4')]
class RequestGroupAction extends BackboneElement
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
        /** @var StringPrimitive|string|null description Short description of the action */
        public StringPrimitive|string|null $description = null,
        /** @var StringPrimitive|string|null textEquivalent Static text equivalent of the action, used if the dynamic aspects cannot be interpreted by the receiving system */
        public StringPrimitive|string|null $textEquivalent = null,
        /** @var RequestPriorityType|null priority routine | urgent | asap | stat */
        public ?RequestPriorityType $priority = null,
        /** @var array<CodeableConcept> code Code representing the meaning of the action or sub-actions */
        public array $code = [],
        /** @var array<RelatedArtifact> documentation Supporting documentation for the intended performer of the action */
        public array $documentation = [],
        /** @var array<RequestGroupActionCondition> condition Whether or not the action is applicable */
        public array $condition = [],
        /** @var array<RequestGroupActionRelatedAction> relatedAction Relationship to another action */
        public array $relatedAction = [],
        /** @var DateTimePrimitive|Age|Period|Duration|Range|Timing|null timingX When the action should take place */
        public DateTimePrimitive|Age|Period|Duration|Range|Timing|null $timingX = null,
        /** @var array<Reference> participant Who should perform the action */
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
        /** @var Reference|null resource The target of the action */
        public ?Reference $resource = null,
        /** @var array<RequestGroupAction> action Sub action */
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
