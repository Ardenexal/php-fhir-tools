<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductIngredient
 *
 * @description An ingredient of a manufactured item or pharmaceutical product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'MedicinalProductIngredient',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductIngredient',
    fhirVersion: 'R4B',
)]
class FHIRMedicinalProductIngredient extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Identifier for the ingredient */
        public ?\FHIRIdentifier $identifier = null,
        /** @var FHIRCodeableConcept|null role Ingredient role e.g. Active ingredient, excipient */
        #[NotBlank]
        public ?\FHIRCodeableConcept $role = null,
        /** @var FHIRBoolean|null allergenicIndicator If the ingredient is a known or suspected allergen */
        public ?\FHIRBoolean $allergenicIndicator = null,
        /** @var array<FHIRReference> manufacturer Manufacturer of this Ingredient */
        public array $manufacturer = [],
        /** @var array<FHIRMedicinalProductIngredientSpecifiedSubstance> specifiedSubstance A specified substance that comprises this ingredient */
        public array $specifiedSubstance = [],
        /** @var FHIRMedicinalProductIngredientSubstance|null substance The ingredient substance */
        public ?\FHIRMedicinalProductIngredientSubstance $substance = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
