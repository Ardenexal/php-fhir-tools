<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element CarePlan.activity.detail
 * @description A simple summary of a planned activity suitable for a general care plan system (e.g. form driven) that doesn't know about specific resources such as procedure etc.
 */
class FHIRCarePlanActivityDetail extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCarePlanActivityKindType kind Appointment | CommunicationRequest | DeviceRequest | MedicationRequest | NutritionOrder | Task | ServiceRequest | VisionPrescription */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCarePlanActivityKindType $kind = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
		public array $instantiatesCanonical = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri> instantiatesUri Instantiates external protocol or definition */
		public array $instantiatesUri = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept code Detail type of activity */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> reasonCode Why activity should be done or why activity was prohibited */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> reasonReference Why activity is needed */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> goal Goals this activity relates to */
		public array $goal = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCarePlanActivityStatusType status not-started | scheduled | in-progress | on-hold | completed | cancelled | stopped | unknown | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCarePlanActivityStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept statusReason Reason for current status */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $statusReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean doNotPerform If true, activity is prohibiting action */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $doNotPerform = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTiming|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string scheduledX When activity is to occur */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTiming|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $scheduledX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference location Where it should happen */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> performer Who will be responsible? */
		public array $performer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference productX What is to be administered/supplied */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|null $productX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity dailyAmount How to consume/day? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $dailyAmount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity quantity How much to administer/supply/consume */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string description Extra info describing activity to perform */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $description = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
