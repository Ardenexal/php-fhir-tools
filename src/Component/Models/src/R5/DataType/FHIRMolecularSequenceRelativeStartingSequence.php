<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MolecularSequence.relative.startingSequence
 * @description A sequence that is used as a starting sequence to describe variants that are present in a sequence analyzed.
 */
class FHIRMolecularSequenceRelativeStartingSequence extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept genomeAssembly The genome assembly used for starting sequence, e.g. GRCh38 */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $genomeAssembly = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept chromosome Chromosome Identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $chromosome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference sequenceX The reference sequence that represents the starting sequence */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|null $sequenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger windowStart Start position of the window on the starting sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $windowStart = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger windowEnd End position of the window on the starting sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $windowEnd = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIROrientationTypeType orientation sense | antisense */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIROrientationTypeType $orientation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStrandTypeType strand watson | crick */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRStrandTypeType $strand = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
