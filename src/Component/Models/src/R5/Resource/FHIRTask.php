<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/Task
 * @description A task to be performed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Task', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Task', fhirVersion: 'R5')]
class FHIRTask extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Task Instance Identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical instantiatesCanonical Formal definition of task */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $instantiatesCanonical = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri instantiatesUri Formal definition of task */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $instantiatesUri = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> basedOn Request fulfilled by this task */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier groupIdentifier Requisition or grouper id */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier $groupIdentifier = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> partOf Composite task */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTaskStatusType status draft | requested | received | accepted | + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTaskStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference statusReason Reason for current status */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference $statusReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept businessStatus E.g. "Specimen collected", "IV prepped" */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $businessStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTaskIntentType intent unknown | proposal | plan | order | original-order | reflex-order | filler-order | instance-order | option */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTaskIntentType $intent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean doNotPerform True if Task is prohibiting action */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $doNotPerform = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept code Task Type */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string description Human-readable explanation of task */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference focus What task is acting on */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $focus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference for Beneficiary of the Task */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $for = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference encounter Healthcare event during which this task originated */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod requestedPeriod When the task should be performed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod $requestedPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod executionPeriod Start and end time of execution */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod $executionPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime authoredOn Task Creation Date */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $authoredOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime lastModified Task Last Modified Date */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $lastModified = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference requester Who is asking for task to be done */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $requester = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> requestedPerformer Who should perform Task */
		public array $requestedPerformer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference owner Responsible individual */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $owner = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTaskPerformer> performer Who or what performed the task */
		public array $performer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference location Where task occurs */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> reason Why task is needed */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> insurance Associated insurance coverage */
		public array $insurance = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Comments made about the task */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> relevantHistory Key events in history of the Task */
		public array $relevantHistory = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTaskRestriction restriction Constraints on fulfillment tasks */
		public ?FHIRTaskRestriction $restriction = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTaskInput> input Information used to perform task */
		public array $input = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTaskOutput> output Information produced as part of task */
		public array $output = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
