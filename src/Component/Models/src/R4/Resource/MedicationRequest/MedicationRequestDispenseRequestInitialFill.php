<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationRequest;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;

/**
 * @description Indicates the quantity or duration for the first dispense of the medication.
 */
#[FHIRBackboneElement(parentResource: 'MedicationRequest', elementPath: 'MedicationRequest.dispenseRequest.initialFill', fhirVersion: 'R4')]
class MedicationRequestDispenseRequestInitialFill extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Quantity|null quantity First fill quantity */
        public ?Quantity $quantity = null,
        /** @var Duration|null duration First fill duration */
        public ?Duration $duration = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
