<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates who or what performed the medication administration and how they were involved.
 */
#[FHIRBackboneElement(parentResource: 'MedicationAdministration', elementPath: 'MedicationAdministration.performer', fhirVersion: 'R4B')]
class FHIRMedicationAdministrationPerformer extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null function Type of performance */
        public ?\FHIRCodeableConcept $function = null,
        /** @var FHIRReference|null actor Who performed the medication administration */
        #[NotBlank]
        public ?\FHIRReference $actor = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
