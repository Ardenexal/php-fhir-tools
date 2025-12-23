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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Task Instance Identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical instantiatesCanonical Formal definition of task */
		public ?FHIRCanonical $instantiatesCanonical = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri instantiatesUri Formal definition of task */
		public ?FHIRUri $instantiatesUri = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> basedOn Request fulfilled by this task */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier groupIdentifier Requisition or grouper id */
		public ?FHIRIdentifier $groupIdentifier = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> partOf Composite task */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTaskStatusType status draft | requested | received | accepted | + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRTaskStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference statusReason Reason for current status */
		public ?FHIRCodeableReference $statusReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept businessStatus E.g. "Specimen collected", "IV prepped" */
		public ?FHIRCodeableConcept $businessStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTaskIntentType intent unknown | proposal | plan | order | original-order | reflex-order | filler-order | instance-order | option */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRTaskIntentType $intent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?FHIRRequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean doNotPerform True if Task is prohibiting action */
		public ?FHIRBoolean $doNotPerform = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Task Type */
		public ?FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string description Human-readable explanation of task */
		public FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference focus What task is acting on */
		public ?FHIRReference $focus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference for Beneficiary of the Task */
		public ?FHIRReference $for = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference encounter Healthcare event during which this task originated */
		public ?FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod requestedPeriod When the task should be performed */
		public ?FHIRPeriod $requestedPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod executionPeriod Start and end time of execution */
		public ?FHIRPeriod $executionPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime authoredOn Task Creation Date */
		public ?FHIRDateTime $authoredOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime lastModified Task Last Modified Date */
		public ?FHIRDateTime $lastModified = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference requester Who is asking for task to be done */
		public ?FHIRReference $requester = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> requestedPerformer Who should perform Task */
		public array $requestedPerformer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference owner Responsible individual */
		public ?FHIRReference $owner = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTaskPerformer> performer Who or what performed the task */
		public array $performer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference location Where task occurs */
		public ?FHIRReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> reason Why task is needed */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> insurance Associated insurance coverage */
		public array $insurance = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Comments made about the task */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> relevantHistory Key events in history of the Task */
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
