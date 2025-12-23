<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element MolecularSequence.referenceSeq
 * @description A sequence that is used as a reference to describe variants that are present in a sequence analyzed.
 */
class FHIRMolecularSequenceReferenceSeq extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept chromosome Chromosome containing genetic finding */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $chromosome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string genomeBuild The Genome Build used for reference, following GRCh build versions e.g. 'GRCh 37' */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $genomeBuild = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIROrientationTypeType orientation sense | antisense */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIROrientationTypeType $orientation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept referenceSeqId Reference identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $referenceSeqId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference referenceSeqPointer A pointer to another MolecularSequence entity as reference sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference $referenceSeqPointer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string referenceSeqString A string to represent reference sequence */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $referenceSeqString = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStrandTypeType strand watson | crick */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRStrandTypeType $strand = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger windowStart Start position of the window on the  reference sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $windowStart = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger windowEnd End position of the window on the reference sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $windowEnd = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
