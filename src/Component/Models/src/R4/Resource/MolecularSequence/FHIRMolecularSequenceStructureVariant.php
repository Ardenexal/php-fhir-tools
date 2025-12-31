<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Information about chromosome structure variation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.structureVariant', fhirVersion: 'R4')]
class FHIRMolecularSequenceStructureVariant extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept variantType Structural variant change type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $variantType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean exact Does the structural variant have base pair resolution breakpoints? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean $exact = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger length Structural variant length */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger $length = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMolecularSequenceStructureVariantOuter outer Structural variant outer */
		public ?FHIRMolecularSequenceStructureVariantOuter $outer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMolecularSequenceStructureVariantInner inner Structural variant inner */
		public ?FHIRMolecularSequenceStructureVariantInner $inner = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
