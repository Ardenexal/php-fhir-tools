<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A sequence that is used as a starting sequence to describe variants that are present in a sequence analyzed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.relative.startingSequence', fhirVersion: 'R5')]
class FHIRMolecularSequenceRelativeStartingSequence extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept genomeAssembly The genome assembly used for starting sequence, e.g. GRCh38 */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $genomeAssembly = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept chromosome Chromosome Identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $chromosome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference sequenceX The reference sequence that represents the starting sequence */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|null $sequenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger windowStart Start position of the window on the starting sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $windowStart = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger windowEnd End position of the window on the starting sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $windowEnd = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIROrientationTypeType orientation sense | antisense */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIROrientationTypeType $orientation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRStrandTypeType strand watson | crick */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRStrandTypeType $strand = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
