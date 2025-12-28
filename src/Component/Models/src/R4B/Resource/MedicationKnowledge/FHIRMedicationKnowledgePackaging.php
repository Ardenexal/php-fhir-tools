<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;

/**
 * @description Information that only applies to packages (not products).
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.packaging', fhirVersion: 'R4B')]
class FHIRMedicationKnowledgePackaging extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type A code that defines the specific type of packaging that the medication can be found in */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|null quantity The number of product units the package would contain if fully loaded */
        public ?FHIRQuantity $quantity = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
