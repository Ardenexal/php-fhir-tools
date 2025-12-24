<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDosage;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Dosage for the medication for the specific guidelines.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'MedicationKnowledge',
    elementPath: 'MedicationKnowledge.administrationGuidelines.dosage',
    fhirVersion: 'R4',
)]
class FHIRMedicationKnowledgeAdministrationGuidelinesDosage extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Type of dosage */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRDosage> dosage Dosage for the medication for the specific guidelines */
        public array $dosage = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
