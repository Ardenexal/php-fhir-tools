<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element SubstancePolymer.monomerSet.startingMaterial
 * @description Todo.
 */
class FHIRSubstancePolymerMonomerSetStartingMaterial extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept material Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $material = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean isDefining Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $isDefining = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceAmount amount Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceAmount $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
