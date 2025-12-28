<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Batch numbering.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProductPackaged', elementPath: 'MedicinalProductPackaged.batchIdentifier', fhirVersion: 'R4B')]
class FHIRMedicinalProductPackagedBatchIdentifier extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null outerPackaging A number appearing on the outer packaging of a specific batch */
        #[NotBlank]
        public ?\FHIRIdentifier $outerPackaging = null,
        /** @var FHIRIdentifier|null immediatePackaging A number appearing on the immediate packaging (and not the outer packaging) */
        public ?\FHIRIdentifier $immediatePackaging = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
