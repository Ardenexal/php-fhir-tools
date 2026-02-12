<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence;

/**
 * @description Structural variant inner.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.structureVariant.inner', fhirVersion: 'R4')]
class MolecularSequenceStructureVariantInner extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|int start Structural variant inner start */
		public ?int $start = null,
		/** @var null|int end Structural variant inner end */
		public ?int $end = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
