<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element MolecularSequence.structureVariant.inner
 * @description Structural variant inner.
 */
class FHIRMolecularSequenceStructureVariantInner extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger start Structural variant inner start */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger $start = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger end Structural variant inner end */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger $end = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
