<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Dosage for the medication for the specific guidelines.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicationKnowledge',
    elementPath: 'MedicationKnowledge.indicationGuideline.dosingGuideline.dosage',
    fhirVersion: 'R5',
)]
class FHIRMedicationKnowledgeIndicationGuidelineDosingGuidelineDosage extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Category of dosage for a medication */
        #[NotBlank]
        public ?\FHIRCodeableConcept $type = null,
        /** @var array<FHIRDosage> dosage Dosage for the medication for the specific guidelines */
        public array $dosage = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
