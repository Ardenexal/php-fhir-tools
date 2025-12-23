<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element RequestOrchestration.action
 * @description The actions, if any, produced by the evaluation of the artifact.
 */
class FHIRRequestOrchestrationAction extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string linkId Pointer to specific item from the PlanDefinition */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string prefix User-visible prefix for the action (e.g. 1. or A.) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $prefix = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string title User-visible title */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Short description of the action */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown textEquivalent Static text equivalent of the action, used if the dynamic aspects cannot be interpreted by the receiving system */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $textEquivalent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestPriorityType $priority = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> code Code representing the meaning of the action or sub-actions */
		public array $code = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRelatedArtifact> documentation Supporting documentation for the intended performer of the action */
		public array $documentation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> goal What goals */
		public array $goal = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestOrchestrationActionCondition> condition Whether or not the action is applicable */
		public array $condition = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestOrchestrationActionInput> input Input data requirements */
		public array $input = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestOrchestrationActionOutput> output Output data definition */
		public array $output = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestOrchestrationActionRelatedAction> relatedAction Relationship to another action */
		public array $relatedAction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming timingX When the action should take place */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming|null $timingX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference location Where it should happen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestOrchestrationActionParticipant> participant Who should perform the action */
		public array $participant = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type create | update | remove | fire-event */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionGroupingBehaviorType groupingBehavior visual-group | logical-group | sentence-group */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionGroupingBehaviorType $groupingBehavior = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionSelectionBehaviorType selectionBehavior any | all | all-or-none | exactly-one | at-most-one | one-or-more */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionSelectionBehaviorType $selectionBehavior = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionRequiredBehaviorType requiredBehavior must | could | must-unless-documented */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionRequiredBehaviorType $requiredBehavior = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionPrecheckBehaviorType precheckBehavior yes | no */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionPrecheckBehaviorType $precheckBehavior = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionCardinalityBehaviorType cardinalityBehavior single | multiple */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionCardinalityBehaviorType $cardinalityBehavior = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference resource The target of the action */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri definitionX Description of the activity to be performed */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri|null $definitionX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical transform Transform to apply the template */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $transform = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestOrchestrationActionDynamicValue> dynamicValue Dynamic aspects of the definition */
		public array $dynamicValue = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestOrchestrationAction> action Sub action */
		public array $action = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
