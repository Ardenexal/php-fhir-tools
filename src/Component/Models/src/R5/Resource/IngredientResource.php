<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Ingredient\IngredientManufacturer;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Ingredient\IngredientSubstance;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Ingredient
 *
 * @description An ingredient of a manufactured item or pharmaceutical product.
 */
#[FhirResource(type: 'Ingredient', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Ingredient', fhirVersion: 'R5')]
class IngredientResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var Identifier|null identifier An identifier or code by which the ingredient can be referenced */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $identifier = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var array<Reference> for The product which this ingredient is a constituent part of */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $for = [],
        /** @var CodeableConcept|null role Purpose of the ingredient within the product, e.g. active, inactive */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $role = null,
        /** @var array<CodeableConcept> function Precise action within the drug product, e.g. antioxidant, alkalizing agent */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $function = [],
        /** @var CodeableConcept|null group A classification of the ingredient according to where in the physical item it tends to be used, such the outer shell of a tablet, inner body or ink */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $group = null,
        /** @var bool|null allergenicIndicator If the ingredient is a known or suspected allergen */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $allergenicIndicator = null,
        /** @var MarkdownPrimitive|null comment A place for providing any notes that are relevant to the component, e.g. removed during process, adjusted for loss on drying */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $comment = null,
        /** @var array<IngredientManufacturer> manufacturer An organization that manufactures this ingredient */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Ingredient\IngredientManufacturer',
        )]
        public array $manufacturer = [],
        /** @var IngredientSubstance|null substance The substance that comprises this ingredient */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isRequired: true), NotBlank]
        public ?IngredientSubstance $substance = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
