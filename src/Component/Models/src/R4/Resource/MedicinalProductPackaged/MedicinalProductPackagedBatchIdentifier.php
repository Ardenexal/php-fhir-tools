<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPackaged;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Batch numbering.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProductPackaged', elementPath: 'MedicinalProductPackaged.batchIdentifier', fhirVersion: 'R4')]
class MedicinalProductPackagedBatchIdentifier extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Identifier|null outerPackaging A number appearing on the outer packaging of a specific batch */
        #[NotBlank]
        public ?Identifier $outerPackaging = null,
        /** @var Identifier|null immediatePackaging A number appearing on the immediate packaging (and not the outer packaging) */
        public ?Identifier $immediatePackaging = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
