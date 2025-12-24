<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Categorization of the medication within a formulary or classification system.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.medicineClassification', fhirVersion: 'R4')]
class FHIRMedicationKnowledgeMedicineClassification extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type The type of category for the medication (for example, therapeutic classification, therapeutic sub-classification) */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> classification Specific category assigned to the medication */
        public array $classification = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
