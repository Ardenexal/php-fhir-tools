<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\InventoryItemStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\InventoryItem\InventoryItemAssociation;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\InventoryItem\InventoryItemCharacteristic;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\InventoryItem\InventoryItemDescription;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\InventoryItem\InventoryItemInstance;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\InventoryItem\InventoryItemName;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\InventoryItem\InventoryItemResponsibleOrganization;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/InventoryItem
 *
 * @description A functional description of an inventory item used in inventory and supply-related workflows.
 */
#[FhirResource(type: 'InventoryItem', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/InventoryItem', fhirVersion: 'R5')]
class InventoryItemResource extends AbstractDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRIsModifier(reason: 'This element is labeled as a modifier because the implicit rules may provide additional knowledge about the resource that modifies its meaning or interpretation')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages|5.0.0', strength: 'required')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<AbstractResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the resource that contains them')]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Business identifier for the inventory item */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var InventoryItemStatusCodesType|null status active | inactive | entered-in-error | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/inventoryitem-status|5.0.0', strength: 'required')]
        public ?InventoryItemStatusCodesType $status = null,
        /** @var array<CodeableConcept> category Category or class of the item */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $category = [],
        /** @var array<CodeableConcept> code Code designating the specific type of item */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $code = [],
        /** @var array<InventoryItemName> name The item name(s) - the brand name, or common name, functional name, generic name or others */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\InventoryItem\InventoryItemName',
        )]
        public array $name = [],
        /** @var array<InventoryItemResponsibleOrganization> responsibleOrganization Organization(s) responsible for the product */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\InventoryItem\InventoryItemResponsibleOrganization',
        )]
        public array $responsibleOrganization = [],
        /** @var InventoryItemDescription|null description Descriptive characteristics of the item */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?InventoryItemDescription $description = null,
        /** @var array<CodeableConcept> inventoryStatus The usage status like recalled, in use, discarded */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $inventoryStatus = [],
        /** @var CodeableConcept|null baseUnit The base unit of measure - the unit in which the product is used or counted */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $baseUnit = null,
        /** @var Quantity|null netContent Net content or amount present in the item */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $netContent = null,
        /** @var array<InventoryItemAssociation> association Association with other items or products */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\InventoryItem\InventoryItemAssociation',
        )]
        public array $association = [],
        /** @var array<InventoryItemCharacteristic> characteristic Characteristic of the item */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\InventoryItem\InventoryItemCharacteristic',
        )]
        public array $characteristic = [],
        /** @var InventoryItemInstance|null instance Instances or occurrences of the product */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?InventoryItemInstance $instance = null,
        /** @var Reference|null productReference Link to a product resource used in clinical workflows */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Medication',
            'http://hl7.org/fhir/StructureDefinition/Device',
            'http://hl7.org/fhir/StructureDefinition/NutritionProduct',
            'http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProduct',
        ])]
        public ?Reference $productReference = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
