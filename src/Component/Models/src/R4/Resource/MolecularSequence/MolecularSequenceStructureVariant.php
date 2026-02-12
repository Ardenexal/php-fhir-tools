<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence;

/**
 * @description Information about chromosome structure variation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.structureVariant', fhirVersion: 'R4')]
class MolecularSequenceStructureVariant extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept variantType Structural variant change type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $variantType = null,
		/** @var null|bool exact Does the structural variant have base pair resolution breakpoints? */
		public ?bool $exact = null,
		/** @var null|int length Structural variant length */
		public ?int $length = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceStructureVariantOuter outer Structural variant outer */
		public ?MolecularSequenceStructureVariantOuter $outer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceStructureVariantInner inner Structural variant inner */
		public ?MolecularSequenceStructureVariantInner $inner = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
