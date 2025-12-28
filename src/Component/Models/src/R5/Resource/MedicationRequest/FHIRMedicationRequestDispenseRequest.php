<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Indicates the specific details for the dispense or medication supply part of a medication request (also known as a Medication Prescription or Medication Order).  Note that this information is not always sent with the order.  There may be in some settings (e.g. hospitals) institutional or system support for completing the dispense details in the pharmacy department.
 */
#[FHIRBackboneElement(parentResource: 'MedicationRequest', elementPath: 'MedicationRequest.dispenseRequest', fhirVersion: 'R5')]
class FHIRMedicationRequestDispenseRequest extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRMedicationRequestDispenseRequestInitialFill|null initialFill First fill details */
        public ?\FHIRMedicationRequestDispenseRequestInitialFill $initialFill = null,
        /** @var FHIRDuration|null dispenseInterval Minimum period of time between dispenses */
        public ?\FHIRDuration $dispenseInterval = null,
        /** @var FHIRPeriod|null validityPeriod Time period supply is authorized for */
        public ?\FHIRPeriod $validityPeriod = null,
        /** @var FHIRUnsignedInt|null numberOfRepeatsAllowed Number of refills authorized */
        public ?\FHIRUnsignedInt $numberOfRepeatsAllowed = null,
        /** @var FHIRQuantity|null quantity Amount of medication to supply per dispense */
        public ?\FHIRQuantity $quantity = null,
        /** @var FHIRDuration|null expectedSupplyDuration Number of days supply per dispense */
        public ?\FHIRDuration $expectedSupplyDuration = null,
        /** @var FHIRReference|null dispenser Intended performer of dispense */
        public ?\FHIRReference $dispenser = null,
        /** @var array<FHIRAnnotation> dispenserInstruction Additional information for the dispenser */
        public array $dispenserInstruction = [],
        /** @var FHIRCodeableConcept|null doseAdministrationAid Type of adherence packaging to use for the dispense */
        public ?\FHIRCodeableConcept $doseAdministrationAid = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
