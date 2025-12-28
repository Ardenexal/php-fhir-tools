<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description A sequence that is used as a starting sequence to describe variants that are present in a sequence analyzed.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.relative.startingSequence', fhirVersion: 'R5')]
class FHIRMolecularSequenceRelativeStartingSequence extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null genomeAssembly The genome assembly used for starting sequence, e.g. GRCh38 */
        public ?\FHIRCodeableConcept $genomeAssembly = null,
        /** @var FHIRCodeableConcept|null chromosome Chromosome Identifier */
        public ?\FHIRCodeableConcept $chromosome = null,
        /** @var FHIRCodeableConcept|FHIRString|string|FHIRReference|null sequenceX The reference sequence that represents the starting sequence */
        public \FHIRCodeableConcept|\FHIRString|string|\FHIRReference|null $sequenceX = null,
        /** @var FHIRInteger|null windowStart Start position of the window on the starting sequence */
        public ?\FHIRInteger $windowStart = null,
        /** @var FHIRInteger|null windowEnd End position of the window on the starting sequence */
        public ?\FHIRInteger $windowEnd = null,
        /** @var FHIROrientationTypeType|null orientation sense | antisense */
        public ?\FHIROrientationTypeType $orientation = null,
        /** @var FHIRStrandTypeType|null strand watson | crick */
        public ?\FHIRStrandTypeType $strand = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
