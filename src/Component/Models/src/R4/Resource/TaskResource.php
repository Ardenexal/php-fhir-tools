<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TaskIntentType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TaskStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Task\TaskInput;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Task\TaskOutput;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Task\TaskRestriction;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Task
 *
 * @description A task to be performed.
 */
#[FhirResource(type: 'Task', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Task', fhirVersion: 'R4')]
class TaskResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Task Instance Identifier */
        public array $identifier = [],
        /** @var CanonicalPrimitive|null instantiatesCanonical Formal definition of task */
        public ?CanonicalPrimitive $instantiatesCanonical = null,
        /** @var UriPrimitive|null instantiatesUri Formal definition of task */
        public ?UriPrimitive $instantiatesUri = null,
        /** @var array<Reference> basedOn Request fulfilled by this task */
        public array $basedOn = [],
        /** @var Identifier|null groupIdentifier Requisition or grouper id */
        public ?Identifier $groupIdentifier = null,
        /** @var array<Reference> partOf Composite task */
        public array $partOf = [],
        /** @var TaskStatusType|null status draft | requested | received | accepted | + */
        #[NotBlank]
        public ?TaskStatusType $status = null,
        /** @var CodeableConcept|null statusReason Reason for current status */
        public ?CodeableConcept $statusReason = null,
        /** @var CodeableConcept|null businessStatus E.g. "Specimen collected", "IV prepped" */
        public ?CodeableConcept $businessStatus = null,
        /** @var TaskIntentType|null intent unknown | proposal | plan | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?TaskIntentType $intent = null,
        /** @var RequestPriorityType|null priority routine | urgent | asap | stat */
        public ?RequestPriorityType $priority = null,
        /** @var CodeableConcept|null code Task Type */
        public ?CodeableConcept $code = null,
        /** @var StringPrimitive|string|null description Human-readable explanation of task */
        public StringPrimitive|string|null $description = null,
        /** @var Reference|null focus What task is acting on */
        public ?Reference $focus = null,
        /** @var Reference|null for Beneficiary of the Task */
        public ?Reference $for = null,
        /** @var Reference|null encounter Healthcare event during which this task originated */
        public ?Reference $encounter = null,
        /** @var Period|null executionPeriod Start and end time of execution */
        public ?Period $executionPeriod = null,
        /** @var DateTimePrimitive|null authoredOn Task Creation Date */
        public ?DateTimePrimitive $authoredOn = null,
        /** @var DateTimePrimitive|null lastModified Task Last Modified Date */
        public ?DateTimePrimitive $lastModified = null,
        /** @var Reference|null requester Who is asking for task to be done */
        public ?Reference $requester = null,
        /** @var array<CodeableConcept> performerType Requested performer */
        public array $performerType = [],
        /** @var Reference|null owner Responsible individual */
        public ?Reference $owner = null,
        /** @var Reference|null location Where task occurs */
        public ?Reference $location = null,
        /** @var CodeableConcept|null reasonCode Why task is needed */
        public ?CodeableConcept $reasonCode = null,
        /** @var Reference|null reasonReference Why task is needed */
        public ?Reference $reasonReference = null,
        /** @var array<Reference> insurance Associated insurance coverage */
        public array $insurance = [],
        /** @var array<Annotation> note Comments made about the task */
        public array $note = [],
        /** @var array<Reference> relevantHistory Key events in history of the Task */
        public array $relevantHistory = [],
        /** @var TaskRestriction|null restriction Constraints on fulfillment tasks */
        public ?TaskRestriction $restriction = null,
        /** @var array<TaskInput> input Information used to perform task */
        public array $input = [],
        /** @var array<TaskOutput> output Information produced as part of task */
        public array $output = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
