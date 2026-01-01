<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Subunits are listed in order of decreasing length; sequences of the same length will be ordered by molecular weight; subunits that have identical sequences will be repeated multiple times.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceNucleicAcid', elementPath: 'SubstanceNucleicAcid.subunit', fhirVersion: 'R4B')]
class FHIRSubstanceNucleicAcidSubunit extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRInteger|null subunit Index of linear sequences of nucleic acids in order of decreasing length. Sequences of the same length will be ordered by molecular weight. Subunits that have identical sequences will be repeated and have sequential subscripts */
        public ?FHIRInteger $subunit = null,
        /** @var FHIRString|string|null sequence Actual nucleotide sequence notation from 5' to 3' end using standard single letter codes. In addition to the base sequence, sugar and type of phosphate or non-phosphate linkage should also be captured */
        public FHIRString|string|null $sequence = null,
        /** @var FHIRInteger|null length The length of the sequence shall be captured */
        public ?FHIRInteger $length = null,
        /** @var FHIRAttachment|null sequenceAttachment (TBC) */
        public ?FHIRAttachment $sequenceAttachment = null,
        /** @var FHIRCodeableConcept|null fivePrime The nucleotide present at the 5’ terminal shall be specified based on a controlled vocabulary. Since the sequence is represented from the 5' to the 3' end, the 5’ prime nucleotide is the letter at the first position in the sequence. A separate representation would be redundant */
        public ?FHIRCodeableConcept $fivePrime = null,
        /** @var FHIRCodeableConcept|null threePrime The nucleotide present at the 3’ terminal shall be specified based on a controlled vocabulary. Since the sequence is represented from the 5' to the 3' end, the 5’ prime nucleotide is the letter at the last position in the sequence. A separate representation would be redundant */
        public ?FHIRCodeableConcept $threePrime = null,
        /** @var array<FHIRSubstanceNucleicAcidSubunitLinkage> linkage The linkages between sugar residues will also be captured */
        public array $linkage = [],
        /** @var array<FHIRSubstanceNucleicAcidSubunitSugar> sugar 5.3.6.8.1 Sugar ID (Mandatory) */
        public array $sugar = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
