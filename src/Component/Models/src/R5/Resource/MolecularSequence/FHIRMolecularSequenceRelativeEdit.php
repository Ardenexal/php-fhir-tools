<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Changes in sequence from the starting sequence.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.relative.edit', fhirVersion: 'R5')]
class FHIRMolecularSequenceRelativeEdit extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRInteger|null start Start position of the edit on the starting sequence */
        public ?FHIRInteger $start = null,
        /** @var FHIRInteger|null end End position of the edit on the starting sequence */
        public ?FHIRInteger $end = null,
        /** @var FHIRString|string|null replacementSequence Allele that was observed */
        public FHIRString|string|null $replacementSequence = null,
        /** @var FHIRString|string|null replacedSequence Allele in the starting sequence */
        public FHIRString|string|null $replacedSequence = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
