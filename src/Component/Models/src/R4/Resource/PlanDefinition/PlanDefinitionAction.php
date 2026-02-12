<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinition;

/**
 * @description An action or group of actions to be taken as part of the plan.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action', fhirVersion: 'R4')]
class PlanDefinitionAction extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string prefix User-visible prefix for the action (e.g. 1. or A.) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $prefix = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string title User-visible title */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Brief description of the action */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string textEquivalent Static text equivalent of the action, used if the dynamic aspects cannot be interpreted by the receiving system */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $textEquivalent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType $priority = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> code Code representing the meaning of the action or sub-actions */
		public array $code = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> reason Why the action should be performed */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact> documentation Supporting documentation for the intended performer of the action */
		public array $documentation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive> goalId What goals this action supports */
		public array $goalId = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subjectX Type of individual the action is focused on */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $subjectX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\TriggerDefinition> trigger When the action should be triggered */
		public array $trigger = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinition\PlanDefinitionActionCondition> condition Whether or not the action is applicable */
		public array $condition = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement> input Input data requirements */
		public array $input = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement> output Output data definition */
		public array $output = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinition\PlanDefinitionActionRelatedAction> relatedAction Relationship to another action */
		public array $relatedAction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Age|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing timingX When the action should take place */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Age|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing|null $timingX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinition\PlanDefinitionActionParticipant> participant Who should participate in the action */
		public array $participant = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type create | update | remove | fire-event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionGroupingBehaviorType groupingBehavior visual-group | logical-group | sentence-group */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionGroupingBehaviorType $groupingBehavior = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionSelectionBehaviorType selectionBehavior any | all | all-or-none | exactly-one | at-most-one | one-or-more */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionSelectionBehaviorType $selectionBehavior = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionRequiredBehaviorType requiredBehavior must | could | must-unless-documented */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionRequiredBehaviorType $requiredBehavior = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionPrecheckBehaviorType precheckBehavior yes | no */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionPrecheckBehaviorType $precheckBehavior = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionCardinalityBehaviorType cardinalityBehavior single | multiple */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionCardinalityBehaviorType $cardinalityBehavior = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive definitionX Description of the activity to be performed */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive|null $definitionX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive transform Transform to apply the template */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $transform = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinition\PlanDefinitionActionDynamicValue> dynamicValue Dynamic aspects of the definition */
		public array $dynamicValue = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinition\PlanDefinitionAction> action A sub-action */
		public array $action = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
