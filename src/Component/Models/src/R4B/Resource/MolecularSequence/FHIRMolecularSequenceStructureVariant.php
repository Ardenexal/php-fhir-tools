<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;

/**
 * @description Information about chromosome structure variation.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.structureVariant', fhirVersion: 'R4B')]
class FHIRMolecularSequenceStructureVariant extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null variantType Structural variant change type */
        public ?FHIRCodeableConcept $variantType = null,
        /** @var FHIRBoolean|null exact Does the structural variant have base pair resolution breakpoints? */
        public ?FHIRBoolean $exact = null,
        /** @var FHIRInteger|null length Structural variant length */
        public ?FHIRInteger $length = null,
        /** @var FHIRMolecularSequenceStructureVariantOuter|null outer Structural variant outer */
        public ?FHIRMolecularSequenceStructureVariantOuter $outer = null,
        /** @var FHIRMolecularSequenceStructureVariantInner|null inner Structural variant inner */
        public ?FHIRMolecularSequenceStructureVariantInner $inner = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
