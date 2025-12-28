<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Structural variant inner.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.structureVariant.inner', fhirVersion: 'R4')]
class FHIRMolecularSequenceStructureVariantInner extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRInteger|null start Structural variant inner start */
        public ?\FHIRInteger $start = null,
        /** @var FHIRInteger|null end Structural variant inner end */
        public ?\FHIRInteger $end = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
