<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/Goal
 * @description Describes the intended objective(s) for a patient, group or organization care, for example, weight loss, restoring an activity of daily living, obtaining herd immunity via immunization, meeting a process improvement objective, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Goal', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Goal', fhirVersion: 'R4')]
class GoalResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier External Ids for this goal */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\GoalLifecycleStatusType lifecycleStatus proposed | planned | accepted | active | on-hold | completed | cancelled | entered-in-error | rejected */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\GoalLifecycleStatusType $lifecycleStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept achievementStatus in-progress | improving | worsening | no-change | achieved | sustaining | not-achieved | no-progress | not-attainable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $achievementStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> category E.g. Treatment, dietary, behavioral, etc. */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept priority high-priority | medium-priority | low-priority */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept description Code or text describing goal */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject Who this goal is intended for */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept startX When goal pursuit begins */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|null $startX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Goal\GoalTarget> target Target outcome for the goal */
		public array $target = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive statusDate When goal status took effect */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $statusDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string statusReason Reason for current status */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $statusReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference expressedBy Who's responsible for creating Goal? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $expressedBy = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> addresses Issues addressed by this goal */
		public array $addresses = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Comments about the goal */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> outcomeCode What result was achieved regarding the goal? */
		public array $outcomeCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> outcomeReference Observation that resulted from goal */
		public array $outcomeReference = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
