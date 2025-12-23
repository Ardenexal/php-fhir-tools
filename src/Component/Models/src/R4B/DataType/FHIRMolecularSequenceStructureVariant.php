<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element MolecularSequence.structureVariant
 * @description Information about chromosome structure variation.
 */
class FHIRMolecularSequenceStructureVariant extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept variantType Structural variant change type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $variantType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean exact Does the structural variant have base pair resolution breakpoints? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $exact = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger length Structural variant length */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger $length = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMolecularSequenceStructureVariantOuter outer Structural variant outer */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMolecularSequenceStructureVariantOuter $outer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMolecularSequenceStructureVariantInner inner Structural variant inner */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMolecularSequenceStructureVariantInner $inner = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
