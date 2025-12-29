<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description A sequence that is used as a reference to describe variants that are present in a sequence analyzed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.referenceSeq', fhirVersion: 'R4B')]
class FHIRMolecularSequenceReferenceSeq extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept chromosome Chromosome containing genetic finding */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $chromosome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string genomeBuild The Genome Build used for reference, following GRCh build versions e.g. 'GRCh 37' */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $genomeBuild = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIROrientationTypeType orientation sense | antisense */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIROrientationTypeType $orientation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept referenceSeqId Reference identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $referenceSeqId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference referenceSeqPointer A pointer to another MolecularSequence entity as reference sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $referenceSeqPointer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string referenceSeqString A string to represent reference sequence */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $referenceSeqString = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRStrandTypeType strand watson | crick */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRStrandTypeType $strand = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger windowStart Start position of the window on the  reference sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger $windowStart = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger windowEnd End position of the window on the reference sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger $windowEnd = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
