<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRInventoryItemStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/InventoryItem
 *
 * @description A functional description of an inventory item used in inventory and supply-related workflows.
 */
#[FhirResource(type: 'InventoryItem', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/InventoryItem', fhirVersion: 'R5')]
class FHIRInventoryItem extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier for the inventory item */
        public array $identifier = [],
        /** @var FHIRInventoryItemStatusCodesType|null status active | inactive | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIRInventoryItemStatusCodesType $status = null,
        /** @var array<FHIRCodeableConcept> category Category or class of the item */
        public array $category = [],
        /** @var array<FHIRCodeableConcept> code Code designating the specific type of item */
        public array $code = [],
        /** @var array<FHIRInventoryItemName> name The item name(s) - the brand name, or common name, functional name, generic name or others */
        public array $name = [],
        /** @var array<FHIRInventoryItemResponsibleOrganization> responsibleOrganization Organization(s) responsible for the product */
        public array $responsibleOrganization = [],
        /** @var FHIRInventoryItemDescription|null description Descriptive characteristics of the item */
        public ?FHIRInventoryItemDescription $description = null,
        /** @var array<FHIRCodeableConcept> inventoryStatus The usage status like recalled, in use, discarded */
        public array $inventoryStatus = [],
        /** @var FHIRCodeableConcept|null baseUnit The base unit of measure - the unit in which the product is used or counted */
        public ?FHIRCodeableConcept $baseUnit = null,
        /** @var FHIRQuantity|null netContent Net content or amount present in the item */
        public ?FHIRQuantity $netContent = null,
        /** @var array<FHIRInventoryItemAssociation> association Association with other items or products */
        public array $association = [],
        /** @var array<FHIRInventoryItemCharacteristic> characteristic Characteristic of the item */
        public array $characteristic = [],
        /** @var FHIRInventoryItemInstance|null instance Instances or occurrences of the product */
        public ?FHIRInventoryItemInstance $instance = null,
        /** @var FHIRReference|null productReference Link to a product resource used in clinical workflows */
        public ?FHIRReference $productReference = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
