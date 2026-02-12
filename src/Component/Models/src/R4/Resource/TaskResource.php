<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/Task
 * @description A task to be performed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Task', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Task', fhirVersion: 'R4')]
class TaskResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Task Instance Identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive instantiatesCanonical Formal definition of task */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $instantiatesCanonical = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive instantiatesUri Formal definition of task */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $instantiatesUri = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> basedOn Request fulfilled by this task */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier groupIdentifier Requisition or grouper id */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $groupIdentifier = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> partOf Composite task */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\TaskStatusType status draft | requested | received | accepted | + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\TaskStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept statusReason Reason for current status */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $statusReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept businessStatus E.g. "Specimen collected", "IV prepped" */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $businessStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\TaskIntentType intent unknown | proposal | plan | order | original-order | reflex-order | filler-order | instance-order | option */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\TaskIntentType $intent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Task Type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Human-readable explanation of task */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference focus What task is acting on */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $focus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference for Beneficiary of the Task */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $for = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference encounter Healthcare event during which this task originated */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period executionPeriod Start and end time of execution */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $executionPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive authoredOn Task Creation Date */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $authoredOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive lastModified Task Last Modified Date */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $lastModified = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference requester Who is asking for task to be done */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $requester = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> performerType Requested performer */
		public array $performerType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference owner Responsible individual */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $owner = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference location Where task occurs */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept reasonCode Why task is needed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $reasonCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference reasonReference Why task is needed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $reasonReference = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> insurance Associated insurance coverage */
		public array $insurance = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Comments made about the task */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> relevantHistory Key events in history of the Task */
		public array $relevantHistory = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\Task\TaskRestriction restriction Constraints on fulfillment tasks */
		public ?Task\TaskRestriction $restriction = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Task\TaskInput> input Information used to perform task */
		public array $input = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Task\TaskOutput> output Information produced as part of task */
		public array $output = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
