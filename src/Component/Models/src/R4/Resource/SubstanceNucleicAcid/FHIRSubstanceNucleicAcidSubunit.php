<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Subunits are listed in order of decreasing length; sequences of the same length will be ordered by molecular weight; subunits that have identical sequences will be repeated multiple times.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceNucleicAcid', elementPath: 'SubstanceNucleicAcid.subunit', fhirVersion: 'R4')]
class FHIRSubstanceNucleicAcidSubunit extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger subunit Index of linear sequences of nucleic acids in order of decreasing length. Sequences of the same length will be ordered by molecular weight. Subunits that have identical sequences will be repeated and have sequential subscripts */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger $subunit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string sequence Actual nucleotide sequence notation from 5' to 3' end using standard single letter codes. In addition to the base sequence, sugar and type of phosphate or non-phosphate linkage should also be captured */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $sequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger length The length of the sequence shall be captured */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger $length = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment sequenceAttachment (TBC) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment $sequenceAttachment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept fivePrime The nucleotide present at the 5’ terminal shall be specified based on a controlled vocabulary. Since the sequence is represented from the 5' to the 3' end, the 5’ prime nucleotide is the letter at the first position in the sequence. A separate representation would be redundant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $fivePrime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept threePrime The nucleotide present at the 3’ terminal shall be specified based on a controlled vocabulary. Since the sequence is represented from the 5' to the 3' end, the 5’ prime nucleotide is the letter at the last position in the sequence. A separate representation would be redundant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $threePrime = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceNucleicAcidSubunitLinkage> linkage The linkages between sugar residues will also be captured */
		public array $linkage = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceNucleicAcidSubunitSugar> sugar 5.3.6.8.1 Sugar ID (Mandatory) */
		public array $sugar = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
