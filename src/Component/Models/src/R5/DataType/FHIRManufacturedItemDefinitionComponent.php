<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ManufacturedItemDefinition.component
 * @description Physical parts of the manufactured item, that it is intrisically made from. This is distinct from the ingredients that are part of its chemical makeup.
 */
class FHIRManufacturedItemDefinitionComponent extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Defining type of the component e.g. shell, layer, ink */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> function The function of this component within the item e.g. delivers active ingredient, masks taste */
		public array $function = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity> amount The measurable amount of total quantity of all substances in the component, expressable in different ways (e.g. by mass or volume) */
		public array $amount = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRManufacturedItemDefinitionComponentConstituent> constituent A reference to a constituent of the manufactured item as a whole, linked here so that its component location within the item can be indicated. This not where the item's ingredient are primarily stated (for which see Ingredient.for or ManufacturedItemDefinition.ingredient) */
		public array $constituent = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRManufacturedItemDefinitionProperty> property General characteristics of this component */
		public array $property = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRManufacturedItemDefinitionComponent> component A component that this component contains or is made from */
		public array $component = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
