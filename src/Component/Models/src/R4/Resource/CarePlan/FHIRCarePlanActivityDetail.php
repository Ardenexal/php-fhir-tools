<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description A simple summary of a planned activity suitable for a general care plan system (e.g. form driven) that doesn't know about specific resources such as procedure etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CarePlan', elementPath: 'CarePlan.activity.detail', fhirVersion: 'R4')]
class FHIRCarePlanActivityDetail extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCarePlanActivityKindType kind Appointment | CommunicationRequest | DeviceRequest | MedicationRequest | NutritionOrder | Task | ServiceRequest | VisionPrescription */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCarePlanActivityKindType $kind = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
		public array $instantiatesCanonical = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri> instantiatesUri Instantiates external protocol or definition */
		public array $instantiatesUri = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept code Detail type of activity */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> reasonCode Why activity should be done or why activity was prohibited */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> reasonReference Why activity is needed */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> goal Goals this activity relates to */
		public array $goal = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCarePlanActivityStatusType status not-started | scheduled | in-progress | on-hold | completed | cancelled | stopped | unknown | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCarePlanActivityStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept statusReason Reason for current status */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $statusReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean doNotPerform If true, activity is prohibiting action */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean $doNotPerform = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTiming|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string scheduledX When activity is to occur */
		public FHIRTiming|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $scheduledX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference location Where it should happen */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> performer Who will be responsible? */
		public array $performer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference productX What is to be administered/supplied */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference|null $productX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity dailyAmount How to consume/day? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity $dailyAmount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity quantity How much to administer/supply/consume */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string description Extra info describing activity to perform */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $description = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
