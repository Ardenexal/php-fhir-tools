<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element SubstancePolymer.monomerSet.startingMaterial
 * @description The starting materials - monomer(s) used in the synthesis of the polymer.
 */
class FHIRSubstancePolymerMonomerSetStartingMaterial extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code The type of substance for this starting material */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept category Substance high level category, e.g. chemical substance */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean isDefining Used to specify whether the attribute described is a defining element for the unique identification of the polymer */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $isDefining = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity amount A percentage */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
