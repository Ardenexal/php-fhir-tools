<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element PlanDefinition.action
 * @description An action or group of actions to be taken as part of the plan. For example, in clinical care, an action would be to prescribe a particular indicated medication, or perform a particular test as appropriate. In pharmaceutical quality, an action would be the test that needs to be performed on a drug product as defined in the quality specification.
 */
class FHIRPlanDefinitionAction extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string linkId Unique id for the action in the PlanDefinition */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string prefix User-visible prefix for the action (e.g. 1. or A.) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $prefix = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string title User-visible title */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Brief description of the action */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown textEquivalent Static text equivalent of the action, used if the dynamic aspects cannot be interpreted by the receiving system */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $textEquivalent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Code representing the meaning of the action or sub-actions */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> reason Why the action should be performed */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRelatedArtifact> documentation Supporting documentation for the intended performer of the action */
		public array $documentation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId> goalId What goals this action supports */
		public array $goalId = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical subjectX Type of individual the action is focused on */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical|null $subjectX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTriggerDefinition> trigger When the action should be triggered */
		public array $trigger = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPlanDefinitionActionCondition> condition Whether or not the action is applicable */
		public array $condition = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPlanDefinitionActionInput> input Input data requirements */
		public array $input = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPlanDefinitionActionOutput> output Output data definition */
		public array $output = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPlanDefinitionActionRelatedAction> relatedAction Relationship to another action */
		public array $relatedAction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming timingX When the action should take place */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming|null $timingX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference location Where it should happen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPlanDefinitionActionParticipant> participant Who should participate in the action */
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri definitionX Description of the activity to be performed */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri|null $definitionX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical transform Transform to apply the template */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $transform = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPlanDefinitionActionDynamicValue> dynamicValue Dynamic aspects of the definition */
		public array $dynamicValue = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPlanDefinitionAction> action A sub-action */
		public array $action = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
