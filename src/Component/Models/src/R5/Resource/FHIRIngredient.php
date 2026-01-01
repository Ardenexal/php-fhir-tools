<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Ingredient
 *
 * @description An ingredient of a manufactured item or pharmaceutical product.
 */
#[FhirResource(type: 'Ingredient', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Ingredient', fhirVersion: 'R5')]
class FHIRIngredient extends FHIRDomainResource
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
        /** @var FHIRIdentifier|null identifier An identifier or code by which the ingredient can be referenced */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var array<FHIRReference> for The product which this ingredient is a constituent part of */
        public array $for = [],
        /** @var FHIRCodeableConcept|null role Purpose of the ingredient within the product, e.g. active, inactive */
        #[NotBlank]
        public ?FHIRCodeableConcept $role = null,
        /** @var array<FHIRCodeableConcept> function Precise action within the drug product, e.g. antioxidant, alkalizing agent */
        public array $function = [],
        /** @var FHIRCodeableConcept|null group A classification of the ingredient according to where in the physical item it tends to be used, such the outer shell of a tablet, inner body or ink */
        public ?FHIRCodeableConcept $group = null,
        /** @var FHIRBoolean|null allergenicIndicator If the ingredient is a known or suspected allergen */
        public ?FHIRBoolean $allergenicIndicator = null,
        /** @var FHIRMarkdown|null comment A place for providing any notes that are relevant to the component, e.g. removed during process, adjusted for loss on drying */
        public ?FHIRMarkdown $comment = null,
        /** @var array<FHIRIngredientManufacturer> manufacturer An organization that manufactures this ingredient */
        public array $manufacturer = [],
        /** @var FHIRIngredientSubstance|null substance The substance that comprises this ingredient */
        #[NotBlank]
        public ?FHIRIngredientSubstance $substance = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
