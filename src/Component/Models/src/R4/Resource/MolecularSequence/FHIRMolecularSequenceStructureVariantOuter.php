<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Structural variant outer.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.structureVariant.outer', fhirVersion: 'R4')]
class FHIRMolecularSequenceStructureVariantOuter extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger start Structural variant outer start */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger $start = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger end Structural variant outer end */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger $end = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
