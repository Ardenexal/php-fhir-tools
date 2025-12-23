<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element MedicationKnowledge.ingredient
 * @description Identifies a particular constituent of interest in the product.
 */
class FHIRMedicationKnowledgeIngredient extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference itemX Medication(s) or substance(s) contained in the medication */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|null $itemX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean isActive Active ingredient indicator */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $isActive = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRatio strength Quantity of ingredient present */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRatio $strength = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
