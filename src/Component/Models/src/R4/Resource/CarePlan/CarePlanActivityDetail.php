<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CarePlan;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CarePlanActivityKindType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CarePlanActivityStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A simple summary of a planned activity suitable for a general care plan system (e.g. form driven) that doesn't know about specific resources such as procedure etc.
 */
#[FHIRBackboneElement(parentResource: 'CarePlan', elementPath: 'CarePlan.activity.detail', fhirVersion: 'R4')]
class CarePlanActivityDetail extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CarePlanActivityKindType|null kind Appointment | CommunicationRequest | DeviceRequest | MedicationRequest | NutritionOrder | Task | ServiceRequest | VisionPrescription */
        public ?CarePlanActivityKindType $kind = null,
        /** @var array<CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<UriPrimitive> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var CodeableConcept|null code Detail type of activity */
        public ?CodeableConcept $code = null,
        /** @var array<CodeableConcept> reasonCode Why activity should be done or why activity was prohibited */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why activity is needed */
        public array $reasonReference = [],
        /** @var array<Reference> goal Goals this activity relates to */
        public array $goal = [],
        /** @var CarePlanActivityStatusType|null status not-started | scheduled | in-progress | on-hold | completed | cancelled | stopped | unknown | entered-in-error */
        #[NotBlank]
        public ?CarePlanActivityStatusType $status = null,
        /** @var CodeableConcept|null statusReason Reason for current status */
        public ?CodeableConcept $statusReason = null,
        /** @var bool|null doNotPerform If true, activity is prohibiting action */
        public ?bool $doNotPerform = null,
        /** @var Timing|Period|StringPrimitive|string|null scheduledX When activity is to occur */
        public Timing|Period|StringPrimitive|string|null $scheduledX = null,
        /** @var Reference|null location Where it should happen */
        public ?Reference $location = null,
        /** @var array<Reference> performer Who will be responsible? */
        public array $performer = [],
        /** @var CodeableConcept|Reference|null productX What is to be administered/supplied */
        public CodeableConcept|Reference|null $productX = null,
        /** @var Quantity|null dailyAmount How to consume/day? */
        public ?Quantity $dailyAmount = null,
        /** @var Quantity|null quantity How much to administer/supply/consume */
        public ?Quantity $quantity = null,
        /** @var StringPrimitive|string|null description Extra info describing activity to perform */
        public StringPrimitive|string|null $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
