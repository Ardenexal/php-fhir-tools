<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description Information about chromosome structure variation.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.structureVariant', fhirVersion: 'R4')]
class MolecularSequenceStructureVariant extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null variantType Structural variant change type */
        public ?CodeableConcept $variantType = null,
        /** @var bool|null exact Does the structural variant have base pair resolution breakpoints? */
        public ?bool $exact = null,
        /** @var int|null length Structural variant length */
        public ?int $length = null,
        /** @var MolecularSequenceStructureVariantOuter|null outer Structural variant outer */
        public ?MolecularSequenceStructureVariantOuter $outer = null,
        /** @var MolecularSequenceStructureVariantInner|null inner Structural variant inner */
        public ?MolecularSequenceStructureVariantInner $inner = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
