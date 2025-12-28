<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Changes in sequence from the starting sequence.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.relative.edit', fhirVersion: 'R5')]
class FHIRMolecularSequenceRelativeEdit extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger start Start position of the edit on the starting sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $start = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger end End position of the edit on the starting sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $end = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string replacementSequence Allele that was observed */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $replacementSequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string replacedSequence Allele in the starting sequence */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $replacedSequence = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
