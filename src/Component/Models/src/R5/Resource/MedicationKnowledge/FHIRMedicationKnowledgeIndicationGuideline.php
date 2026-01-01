<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;

/**
 * @description Guidelines or protocols that are applicable for the administration of the medication based on indication.
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.indicationGuideline', fhirVersion: 'R5')]
class FHIRMedicationKnowledgeIndicationGuideline extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableReference> indication Indication for use that applies to the specific administration guideline */
        public array $indication = [],
        /** @var array<FHIRMedicationKnowledgeIndicationGuidelineDosingGuideline> dosingGuideline Guidelines for dosage of the medication */
        public array $dosingGuideline = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
