<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationRequest;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;

/**
 * @description Indicates the specific details for the dispense or medication supply part of a medication request (also known as a Medication Prescription or Medication Order).  Note that this information is not always sent with the order.  There may be in some settings (e.g. hospitals) institutional or system support for completing the dispense details in the pharmacy department.
 */
#[FHIRBackboneElement(parentResource: 'MedicationRequest', elementPath: 'MedicationRequest.dispenseRequest', fhirVersion: 'R4')]
class MedicationRequestDispenseRequest extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var MedicationRequestDispenseRequestInitialFill|null initialFill First fill details */
        public ?MedicationRequestDispenseRequestInitialFill $initialFill = null,
        /** @var Duration|null dispenseInterval Minimum period of time between dispenses */
        public ?Duration $dispenseInterval = null,
        /** @var Period|null validityPeriod Time period supply is authorized for */
        public ?Period $validityPeriod = null,
        /** @var UnsignedIntPrimitive|null numberOfRepeatsAllowed Number of refills authorized */
        public ?UnsignedIntPrimitive $numberOfRepeatsAllowed = null,
        /** @var Quantity|null quantity Amount of medication to supply per dispense */
        public ?Quantity $quantity = null,
        /** @var Duration|null expectedSupplyDuration Number of days supply per dispense */
        public ?Duration $expectedSupplyDuration = null,
        /** @var Reference|null performer Intended dispenser */
        public ?Reference $performer = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
