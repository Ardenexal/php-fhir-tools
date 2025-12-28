<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A sequence defined relative to another sequence.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.relative', fhirVersion: 'R5')]
class FHIRMolecularSequenceRelative extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null coordinateSystem Ways of identifying nucleotides or amino acids within a sequence */
        #[NotBlank]
        public ?FHIRCodeableConcept $coordinateSystem = null,
        /** @var FHIRInteger|null ordinalPosition Indicates the order in which the sequence should be considered when putting multiple 'relative' elements together */
        public ?FHIRInteger $ordinalPosition = null,
        /** @var FHIRRange|null sequenceRange Indicates the nucleotide range in the composed sequence when multiple 'relative' elements are used together */
        public ?FHIRRange $sequenceRange = null,
        /** @var FHIRMolecularSequenceRelativeStartingSequence|null startingSequence A sequence used as starting sequence */
        public ?FHIRMolecularSequenceRelativeStartingSequence $startingSequence = null,
        /** @var array<FHIRMolecularSequenceRelativeEdit> edit Changes in sequence from the starting sequence */
        public array $edit = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
