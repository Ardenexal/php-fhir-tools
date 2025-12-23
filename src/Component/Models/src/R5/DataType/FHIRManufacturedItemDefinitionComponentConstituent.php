<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ManufacturedItemDefinition.component.constituent
 * @description A reference to a constituent of the manufactured item as a whole, linked here so that its component location within the item can be indicated. This not where the item's ingredient are primarily stated (for which see Ingredient.for or ManufacturedItemDefinition.ingredient).
 */
class FHIRManufacturedItemDefinitionComponentConstituent extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity> amount The measurable amount of the substance, expressable in different ways (e.g. by mass or volume) */
		public array $amount = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> location The physical location of the constituent/ingredient within the component */
		public array $location = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> function The function of this constituent within the component e.g. binder */
		public array $function = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> hasIngredient The ingredient that is the constituent of the given component */
		public array $hasIngredient = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
