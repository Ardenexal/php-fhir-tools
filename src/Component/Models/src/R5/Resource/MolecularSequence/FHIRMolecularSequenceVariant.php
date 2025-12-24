<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description The definition of variant here originates from Sequence ontology ([variant_of](http://www.sequenceontology.org/browser/current_svn/term/variant_of)). This element can represent amino acid or nucleic sequence change(including insertion,deletion,SNP,etc.)  It can represent some complex mutation or segment variation with the assist of CIGAR string.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.variant', fhirVersion: 'R4B')]
class FHIRMolecularSequenceVariant extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRInteger|null start Start position of the variant on the  reference sequence */
        public ?FHIRInteger $start = null,
        /** @var FHIRInteger|null end End position of the variant on the reference sequence */
        public ?FHIRInteger $end = null,
        /** @var FHIRString|string|null observedAllele Allele that was observed */
        public FHIRString|string|null $observedAllele = null,
        /** @var FHIRString|string|null referenceAllele Allele in the reference sequence */
        public FHIRString|string|null $referenceAllele = null,
        /** @var FHIRString|string|null cigar Extended CIGAR string for aligning the sequence with reference bases */
        public FHIRString|string|null $cigar = null,
        /** @var FHIRReference|null variantPointer Pointer to observed variant information */
        public ?FHIRReference $variantPointer = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
