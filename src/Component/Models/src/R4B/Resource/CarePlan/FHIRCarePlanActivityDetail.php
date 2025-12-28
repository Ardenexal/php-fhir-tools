<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A simple summary of a planned activity suitable for a general care plan system (e.g. form driven) that doesn't know about specific resources such as procedure etc.
 */
#[FHIRBackboneElement(parentResource: 'CarePlan', elementPath: 'CarePlan.activity.detail', fhirVersion: 'R4B')]
class FHIRCarePlanActivityDetail extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCarePlanActivityKindType|null kind Appointment | CommunicationRequest | DeviceRequest | MedicationRequest | NutritionOrder | Task | ServiceRequest | VisionPrescription */
        public ?FHIRCarePlanActivityKindType $kind = null,
        /** @var array<FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<FHIRUri> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var FHIRCodeableConcept|null code Detail type of activity */
        public ?FHIRCodeableConcept $code = null,
        /** @var array<FHIRCodeableConcept> reasonCode Why activity should be done or why activity was prohibited */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Why activity is needed */
        public array $reasonReference = [],
        /** @var array<FHIRReference> goal Goals this activity relates to */
        public array $goal = [],
        /** @var FHIRCarePlanActivityStatusType|null status not-started | scheduled | in-progress | on-hold | completed | cancelled | stopped | unknown | entered-in-error */
        #[NotBlank]
        public ?FHIRCarePlanActivityStatusType $status = null,
        /** @var FHIRCodeableConcept|null statusReason Reason for current status */
        public ?FHIRCodeableConcept $statusReason = null,
        /** @var FHIRBoolean|null doNotPerform If true, activity is prohibiting action */
        public ?FHIRBoolean $doNotPerform = null,
        /** @var FHIRTiming|FHIRPeriod|FHIRString|string|null scheduledX When activity is to occur */
        public FHIRTiming|FHIRPeriod|FHIRString|string|null $scheduledX = null,
        /** @var FHIRReference|null location Where it should happen */
        public ?FHIRReference $location = null,
        /** @var array<FHIRReference> performer Who will be responsible? */
        public array $performer = [],
        /** @var FHIRCodeableConcept|FHIRReference|null productX What is to be administered/supplied */
        public FHIRCodeableConcept|FHIRReference|null $productX = null,
        /** @var FHIRQuantity|null dailyAmount How to consume/day? */
        public ?FHIRQuantity $dailyAmount = null,
        /** @var FHIRQuantity|null quantity How much to administer/supply/consume */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRString|string|null description Extra info describing activity to perform */
        public FHIRString|string|null $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
