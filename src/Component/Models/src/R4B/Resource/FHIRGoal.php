<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/Goal
 * @description Describes the intended objective(s) for a patient, group or organization care, for example, weight loss, restoring an activity of daily living, obtaining herd immunity via immunization, meeting a process improvement objective, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Goal', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Goal', fhirVersion: 'R4B')]
class FHIRGoal extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier> identifier External Ids for this goal */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRGoalLifecycleStatusType lifecycleStatus proposed | planned | accepted | active | on-hold | completed | cancelled | entered-in-error | rejected */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRGoalLifecycleStatusType $lifecycleStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept achievementStatus in-progress | improving | worsening | no-change | achieved | sustaining | not-achieved | no-progress | not-attainable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $achievementStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> category E.g. Treatment, dietary, behavioral, etc. */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept priority high-priority | medium-priority | low-priority */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept description Code or text describing goal */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference subject Who this goal is intended for */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept startX When goal pursuit begins */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept|null $startX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRGoalTarget> target Target outcome for the goal */
		public array $target = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate statusDate When goal status took effect */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate $statusDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string statusReason Reason for current status */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $statusReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference expressedBy Who's responsible for creating Goal? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $expressedBy = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> addresses Issues addressed by this goal */
		public array $addresses = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation> note Comments about the goal */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> outcomeCode What result was achieved regarding the goal? */
		public array $outcomeCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> outcomeReference Observation that resulted from goal */
		public array $outcomeReference = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
