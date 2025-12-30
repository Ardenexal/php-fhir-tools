<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTaskIntentType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTaskStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Task
 *
 * @description A task to be performed.
 */
#[FhirResource(type: 'Task', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Task', fhirVersion: 'R5')]
class FHIRTask extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Task Instance Identifier */
        public array $identifier = [],
        /** @var FHIRCanonical|null instantiatesCanonical Formal definition of task */
        public ?FHIRCanonical $instantiatesCanonical = null,
        /** @var FHIRUri|null instantiatesUri Formal definition of task */
        public ?FHIRUri $instantiatesUri = null,
        /** @var array<FHIRReference> basedOn Request fulfilled by this task */
        public array $basedOn = [],
        /** @var FHIRIdentifier|null groupIdentifier Requisition or grouper id */
        public ?FHIRIdentifier $groupIdentifier = null,
        /** @var array<FHIRReference> partOf Composite task */
        public array $partOf = [],
        /** @var FHIRTaskStatusType|null status draft | requested | received | accepted | + */
        #[NotBlank]
        public ?FHIRTaskStatusType $status = null,
        /** @var FHIRCodeableReference|null statusReason Reason for current status */
        public ?FHIRCodeableReference $statusReason = null,
        /** @var FHIRCodeableConcept|null businessStatus E.g. "Specimen collected", "IV prepped" */
        public ?FHIRCodeableConcept $businessStatus = null,
        /** @var FHIRTaskIntentType|null intent unknown | proposal | plan | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?FHIRTaskIntentType $intent = null,
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var FHIRBoolean|null doNotPerform True if Task is prohibiting action */
        public ?FHIRBoolean $doNotPerform = null,
        /** @var FHIRCodeableConcept|null code Task Type */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRString|string|null description Human-readable explanation of task */
        public FHIRString|string|null $description = null,
        /** @var FHIRReference|null focus What task is acting on */
        public ?FHIRReference $focus = null,
        /** @var FHIRReference|null for Beneficiary of the Task */
        public ?FHIRReference $for = null,
        /** @var FHIRReference|null encounter Healthcare event during which this task originated */
        public ?FHIRReference $encounter = null,
        /** @var FHIRPeriod|null requestedPeriod When the task should be performed */
        public ?FHIRPeriod $requestedPeriod = null,
        /** @var FHIRPeriod|null executionPeriod Start and end time of execution */
        public ?FHIRPeriod $executionPeriod = null,
        /** @var FHIRDateTime|null authoredOn Task Creation Date */
        public ?FHIRDateTime $authoredOn = null,
        /** @var FHIRDateTime|null lastModified Task Last Modified Date */
        public ?FHIRDateTime $lastModified = null,
        /** @var FHIRReference|null requester Who is asking for task to be done */
        public ?FHIRReference $requester = null,
        /** @var array<FHIRCodeableReference> requestedPerformer Who should perform Task */
        public array $requestedPerformer = [],
        /** @var FHIRReference|null owner Responsible individual */
        public ?FHIRReference $owner = null,
        /** @var array<FHIRTaskPerformer> performer Who or what performed the task */
        public array $performer = [],
        /** @var FHIRReference|null location Where task occurs */
        public ?FHIRReference $location = null,
        /** @var array<FHIRCodeableReference> reason Why task is needed */
        public array $reason = [],
        /** @var array<FHIRReference> insurance Associated insurance coverage */
        public array $insurance = [],
        /** @var array<FHIRAnnotation> note Comments made about the task */
        public array $note = [],
        /** @var array<FHIRReference> relevantHistory Key events in history of the Task */
        public array $relevantHistory = [],
        /** @var FHIRTaskRestriction|null restriction Constraints on fulfillment tasks */
        public ?FHIRTaskRestriction $restriction = null,
        /** @var array<FHIRTaskInput> input Information used to perform task */
        public array $input = [],
        /** @var array<FHIRTaskOutput> output Information produced as part of task */
        public array $output = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
