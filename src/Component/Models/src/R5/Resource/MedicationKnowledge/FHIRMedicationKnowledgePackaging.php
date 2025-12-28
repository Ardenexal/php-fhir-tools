<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Information that only applies to packages (not products).
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.packaging', fhirVersion: 'R5')]
class FHIRMedicationKnowledgePackaging extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRMedicationKnowledgeCost> cost Cost of the packaged medication */
        public array $cost = [],
        /** @var FHIRReference|null packagedProduct The packaged medication that is being priced */
        public ?\FHIRReference $packagedProduct = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
