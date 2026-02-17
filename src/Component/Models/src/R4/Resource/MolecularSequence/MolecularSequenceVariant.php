<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description The definition of variant here originates from Sequence ontology ([variant_of](http://www.sequenceontology.org/browser/current_svn/term/variant_of)). This element can represent amino acid or nucleic sequence change(including insertion,deletion,SNP,etc.)  It can represent some complex mutation or segment variation with the assist of CIGAR string.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.variant', fhirVersion: 'R4')]
class MolecularSequenceVariant extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var int|null start Start position of the variant on the  reference sequence */
        public ?int $start = null,
        /** @var int|null end End position of the variant on the reference sequence */
        public ?int $end = null,
        /** @var StringPrimitive|string|null observedAllele Allele that was observed */
        public StringPrimitive|string|null $observedAllele = null,
        /** @var StringPrimitive|string|null referenceAllele Allele in the reference sequence */
        public StringPrimitive|string|null $referenceAllele = null,
        /** @var StringPrimitive|string|null cigar Extended CIGAR string for aligning the sequence with reference bases */
        public StringPrimitive|string|null $cigar = null,
        /** @var Reference|null variantPointer Pointer to observed variant information */
        public ?Reference $variantPointer = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
