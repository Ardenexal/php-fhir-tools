<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/InventoryItem
 * @description A functional description of an inventory item used in inventory and supply-related workflows.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'InventoryItem', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/InventoryItem', fhirVersion: 'R5')]
class FHIRInventoryItem extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Business identifier for the inventory item */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRInventoryItemStatusCodesType status active | inactive | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRInventoryItemStatusCodesType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> category Category or class of the item */
		public array $category = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> code Code designating the specific type of item */
		public array $code = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInventoryItemName> name The item name(s) - the brand name, or common name, functional name, generic name or others */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInventoryItemResponsibleOrganization> responsibleOrganization Organization(s) responsible for the product */
		public array $responsibleOrganization = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInventoryItemDescription description Descriptive characteristics of the item */
		public ?FHIRInventoryItemDescription $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> inventoryStatus The usage status like recalled, in use, discarded */
		public array $inventoryStatus = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept baseUnit The base unit of measure - the unit in which the product is used or counted */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $baseUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity netContent Net content or amount present in the item */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $netContent = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInventoryItemAssociation> association Association with other items or products */
		public array $association = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInventoryItemCharacteristic> characteristic Characteristic of the item */
		public array $characteristic = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInventoryItemInstance instance Instances or occurrences of the product */
		public ?FHIRInventoryItemInstance $instance = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference productReference Link to a product resource used in clinical workflows */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $productReference = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
