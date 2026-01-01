<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNutritionProductStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/NutritionProduct
 *
 * @description A food or supplement that is consumed by patients.
 */
#[FhirResource(
    type: 'NutritionProduct',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/NutritionProduct',
    fhirVersion: 'R5',
)]
class FHIRNutritionProduct extends FHIRDomainResource
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
        /** @var FHIRCodeableConcept|null code A code that can identify the detailed nutrients and ingredients in a specific food product */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRNutritionProductStatusType|null status active | inactive | entered-in-error */
        #[NotBlank]
        public ?FHIRNutritionProductStatusType $status = null,
        /** @var array<FHIRCodeableConcept> category Broad product groups or categories used to classify the product, such as Legume and Legume Products, Beverages, or Beef Products */
        public array $category = [],
        /** @var array<FHIRReference> manufacturer Manufacturer, representative or officially responsible for the product */
        public array $manufacturer = [],
        /** @var array<FHIRNutritionProductNutrient> nutrient The product's nutritional information expressed by the nutrients */
        public array $nutrient = [],
        /** @var array<FHIRNutritionProductIngredient> ingredient Ingredients contained in this product */
        public array $ingredient = [],
        /** @var array<FHIRCodeableReference> knownAllergen Known or suspected allergens that are a part of this product */
        public array $knownAllergen = [],
        /** @var array<FHIRNutritionProductCharacteristic> characteristic Specifies descriptive properties of the nutrition product */
        public array $characteristic = [],
        /** @var array<FHIRNutritionProductInstance> instance One or several physical instances or occurrences of the nutrition product */
        public array $instance = [],
        /** @var array<FHIRAnnotation> note Comments made about the product */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
