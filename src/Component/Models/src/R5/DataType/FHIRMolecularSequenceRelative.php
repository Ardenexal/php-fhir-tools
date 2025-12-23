<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MolecularSequence.relative
 * @description A sequence defined relative to another sequence.
 */
class FHIRMolecularSequenceRelative extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept coordinateSystem Ways of identifying nucleotides or amino acids within a sequence */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $coordinateSystem = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger ordinalPosition Indicates the order in which the sequence should be considered when putting multiple 'relative' elements together */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $ordinalPosition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange sequenceRange Indicates the nucleotide range in the composed sequence when multiple 'relative' elements are used together */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange $sequenceRange = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMolecularSequenceRelativeStartingSequence startingSequence A sequence used as starting sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMolecularSequenceRelativeStartingSequence $startingSequence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMolecularSequenceRelativeEdit> edit Changes in sequence from the starting sequence */
		public array $edit = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
