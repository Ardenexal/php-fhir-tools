<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element PackagedProductDefinition.package
 * @description A packaging item, as a container for medically related items, possibly with other packaging items within, or a packaging component, such as bottle cap (which is not a device or a medication manufactured item).
 */
class FHIRPackagedProductDefinitionPackage extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier An identifier that is specific to this particular part of the packaging. Including possibly a Data Carrier Identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type The physical type of the container of the items */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger quantity The quantity of this level of packaging in the package that contains it (with the outermost level being 1) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> material Material type of the package item */
		public array $material = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> alternateMaterial A possible alternate material for this part of the packaging, that is allowed to be used instead of the usual material */
		public array $alternateMaterial = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPackagedProductDefinitionPackageShelfLifeStorage> shelfLifeStorage Shelf Life and storage information */
		public array $shelfLifeStorage = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> manufacturer Manufacturer of this package Item (multiple means these are all possible manufacturers) */
		public array $manufacturer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPackagedProductDefinitionPackageProperty> property General characteristics of this item */
		public array $property = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPackagedProductDefinitionPackageContainedItem> containedItem The item(s) within the packaging */
		public array $containedItem = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPackagedProductDefinitionPackage> package Allows containers (and parts of containers) within containers, still a single packaged product */
		public array $package = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
