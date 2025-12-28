<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The price of the medication.
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.cost', fhirVersion: 'R5')]
class FHIRMedicationKnowledgeCost extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRPeriod> effectiveDate The date range for which the cost is effective */
        public array $effectiveDate = [],
        /** @var FHIRCodeableConcept|null type The category of the cost information */
        #[NotBlank]
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRString|string|null source The source or owner for the price information */
        public \FHIRString|string|null $source = null,
        /** @var FHIRMoney|FHIRCodeableConcept|null costX The price or category of the cost of the medication */
        #[NotBlank]
        public \FHIRMoney|\FHIRCodeableConcept|null $costX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
