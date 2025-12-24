<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The maximum number of units of the medication that can be dispensed in a period.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.regulatory.maxDispense', fhirVersion: 'R5')]
class FHIRMedicationKnowledgeRegulatoryMaxDispense extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRQuantity|null quantity The maximum number of units of the medication that can be dispensed */
        #[NotBlank]
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRDuration|null period The period that applies to the maximum number of units */
        public ?FHIRDuration $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
