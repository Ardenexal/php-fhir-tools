<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The definition of variant here originates from Sequence ontology ([variant_of](http://www.sequenceontology.org/browser/current_svn/term/variant_of)). This element can represent amino acid or nucleic sequence change(including insertion,deletion,SNP,etc.)  It can represent some complex mutation or segment variation with the assist of CIGAR string.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.variant', fhirVersion: 'R4B')]
class FHIRMolecularSequenceVariant extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger start Start position of the variant on the  reference sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger $start = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger end End position of the variant on the reference sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger $end = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string observedAllele Allele that was observed */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $observedAllele = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string referenceAllele Allele in the reference sequence */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $referenceAllele = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string cigar Extended CIGAR string for aligning the sequence with reference bases */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $cigar = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference variantPointer Pointer to observed variant information */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $variantPointer = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
