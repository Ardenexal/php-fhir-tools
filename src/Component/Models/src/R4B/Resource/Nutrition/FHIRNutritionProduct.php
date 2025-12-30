<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNutritionProductStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/NutritionProduct
 *
 * @description A food or fluid product that is consumed by patients.
 */
#[FhirResource(
    type: 'NutritionProduct',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/NutritionProduct',
    fhirVersion: 'R4B',
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
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRNutritionProductStatusType|null status active | inactive | entered-in-error */
        #[NotBlank]
        public ?FHIRNutritionProductStatusType $status = null,
        /** @var array<FHIRCodeableConcept> category A category or class of the nutrition product (halal, kosher, gluten free, vegan, etc) */
        public array $category = [],
        /** @var FHIRCodeableConcept|null code A code designating a specific type of nutritional product */
        public ?FHIRCodeableConcept $code = null,
        /** @var array<FHIRReference> manufacturer Manufacturer, representative or officially responsible for the product */
        public array $manufacturer = [],
        /** @var array<FHIRNutritionProductNutrient> nutrient The product's nutritional information expressed by the nutrients */
        public array $nutrient = [],
        /** @var array<FHIRNutritionProductIngredient> ingredient Ingredients contained in this product */
        public array $ingredient = [],
        /** @var array<FHIRCodeableReference> knownAllergen Known or suspected allergens that are a part of this product */
        public array $knownAllergen = [],
        /** @var array<FHIRNutritionProductProductCharacteristic> productCharacteristic Specifies descriptive properties of the nutrition product */
        public array $productCharacteristic = [],
        /** @var FHIRNutritionProductInstance|null instance One or several physical instances or occurrences of the nutrition product */
        public ?FHIRNutritionProductInstance $instance = null,
        /** @var array<FHIRAnnotation> note Comments made about the product */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
