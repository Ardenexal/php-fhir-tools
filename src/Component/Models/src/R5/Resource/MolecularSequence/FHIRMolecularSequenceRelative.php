<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A sequence defined relative to another sequence.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.relative', fhirVersion: 'R5')]
class FHIRMolecularSequenceRelative extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept coordinateSystem Ways of identifying nucleotides or amino acids within a sequence */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $coordinateSystem = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger ordinalPosition Indicates the order in which the sequence should be considered when putting multiple 'relative' elements together */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $ordinalPosition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange sequenceRange Indicates the nucleotide range in the composed sequence when multiple 'relative' elements are used together */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange $sequenceRange = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMolecularSequenceRelativeStartingSequence startingSequence A sequence used as starting sequence */
		public ?FHIRMolecularSequenceRelativeStartingSequence $startingSequence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMolecularSequenceRelativeEdit> edit Changes in sequence from the starting sequence */
		public array $edit = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
