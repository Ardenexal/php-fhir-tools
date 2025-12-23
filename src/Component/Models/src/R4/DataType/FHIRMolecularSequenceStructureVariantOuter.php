<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element MolecularSequence.structureVariant.outer
 * @description Structural variant outer.
 */
class FHIRMolecularSequenceStructureVariantOuter extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger start Structural variant outer start */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $start = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger end Structural variant outer end */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $end = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
