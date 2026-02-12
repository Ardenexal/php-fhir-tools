<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence;

/**
 * @description A sequence that is used as a reference to describe variants that are present in a sequence analyzed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.referenceSeq', fhirVersion: 'R4')]
class MolecularSequenceReferenceSeq extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept chromosome Chromosome containing genetic finding */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $chromosome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string genomeBuild The Genome Build used for reference, following GRCh build versions e.g. 'GRCh 37' */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $genomeBuild = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\OrientationTypeType orientation sense | antisense */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\OrientationTypeType $orientation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept referenceSeqId Reference identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $referenceSeqId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference referenceSeqPointer A pointer to another MolecularSequence entity as reference sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $referenceSeqPointer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string referenceSeqString A string to represent reference sequence */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $referenceSeqString = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\StrandTypeType strand watson | crick */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\StrandTypeType $strand = null,
		/** @var null|int windowStart Start position of the window on the  reference sequence */
		public ?int $windowStart = null,
		/** @var null|int windowEnd End position of the window on the reference sequence */
		public ?int $windowEnd = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
