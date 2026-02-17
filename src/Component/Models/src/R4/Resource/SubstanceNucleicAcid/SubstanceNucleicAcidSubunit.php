<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceNucleicAcid;

/**
 * @description Subunits are listed in order of decreasing length; sequences of the same length will be ordered by molecular weight; subunits that have identical sequences will be repeated multiple times.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceNucleicAcid', elementPath: 'SubstanceNucleicAcid.subunit', fhirVersion: 'R4')]
class SubstanceNucleicAcidSubunit extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|int subunit Index of linear sequences of nucleic acids in order of decreasing length. Sequences of the same length will be ordered by molecular weight. Subunits that have identical sequences will be repeated and have sequential subscripts */
		public ?int $subunit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string sequence Actual nucleotide sequence notation from 5' to 3' end using standard single letter codes. In addition to the base sequence, sugar and type of phosphate or non-phosphate linkage should also be captured */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $sequence = null,
		/** @var null|int length The length of the sequence shall be captured */
		public ?int $length = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment sequenceAttachment (TBC) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment $sequenceAttachment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept fivePrime The nucleotide present at the 5’ terminal shall be specified based on a controlled vocabulary. Since the sequence is represented from the 5' to the 3' end, the 5’ prime nucleotide is the letter at the first position in the sequence. A separate representation would be redundant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $fivePrime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept threePrime The nucleotide present at the 3’ terminal shall be specified based on a controlled vocabulary. Since the sequence is represented from the 5' to the 3' end, the 5’ prime nucleotide is the letter at the last position in the sequence. A separate representation would be redundant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $threePrime = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceNucleicAcid\SubstanceNucleicAcidSubunitLinkage> linkage The linkages between sugar residues will also be captured */
		public array $linkage = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceNucleicAcid\SubstanceNucleicAcidSubunitSugar> sugar 5.3.6.8.1 Sugar ID (Mandatory) */
		public array $sugar = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
