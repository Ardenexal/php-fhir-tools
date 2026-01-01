<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The performer of the medication treatment.  For devices this is the device that performed the administration of the medication.  An IV Pump would be an example of a device that is performing the administration. Both the IV Pump and the practitioner that set the rate or bolus on the pump can be listed as performers.
 */
#[FHIRBackboneElement(parentResource: 'MedicationAdministration', elementPath: 'MedicationAdministration.performer', fhirVersion: 'R5')]
class FHIRMedicationAdministrationPerformer extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null function Type of performance */
        public ?FHIRCodeableConcept $function = null,
        /** @var FHIRCodeableReference|null actor Who or what performed the medication administration */
        #[NotBlank]
        public ?FHIRCodeableReference $actor = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
