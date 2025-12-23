<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element SubstancePolymer.monomerSet
 * @description Todo.
 */
class FHIRSubstancePolymerMonomerSet extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept ratioType Captures the type of ratio to the entire polymer, e.g. Monomer/Polymer ratio, SRU/Polymer Ratio */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $ratioType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstancePolymerMonomerSetStartingMaterial> startingMaterial The starting materials - monomer(s) used in the synthesis of the polymer */
		public array $startingMaterial = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
