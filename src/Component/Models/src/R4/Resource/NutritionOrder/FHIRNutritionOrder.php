<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/NutritionOrder
 *
 * @description A request to supply a diet, formula feeding (enteral) or oral nutritional supplement to a patient/resident.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'NutritionOrder',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/NutritionOrder',
    fhirVersion: 'R4',
)]
class FHIRNutritionOrder extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Identifiers assigned to this order */
        public array $identifier = [],
        /** @var array<FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<FHIRUri> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<FHIRUri> instantiates Instantiates protocol or definition */
        public array $instantiates = [],
        /** @var FHIRRequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?\FHIRRequestStatusType $status = null,
        /** @var FHIRRequestIntentType|null intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?\FHIRRequestIntentType $intent = null,
        /** @var FHIRReference|null patient The person who requires the diet, formula or nutritional supplement */
        #[NotBlank]
        public ?\FHIRReference $patient = null,
        /** @var FHIRReference|null encounter The encounter associated with this nutrition order */
        public ?\FHIRReference $encounter = null,
        /** @var FHIRDateTime|null dateTime Date and time the nutrition order was requested */
        #[NotBlank]
        public ?\FHIRDateTime $dateTime = null,
        /** @var FHIRReference|null orderer Who ordered the diet, formula or nutritional supplement */
        public ?\FHIRReference $orderer = null,
        /** @var array<FHIRReference> allergyIntolerance List of the patient's food and nutrition-related allergies and intolerances */
        public array $allergyIntolerance = [],
        /** @var array<FHIRCodeableConcept> foodPreferenceModifier Order-specific modifier about the type of food that should be given */
        public array $foodPreferenceModifier = [],
        /** @var array<FHIRCodeableConcept> excludeFoodModifier Order-specific modifier about the type of food that should not be given */
        public array $excludeFoodModifier = [],
        /** @var FHIRNutritionOrderOralDiet|null oralDiet Oral diet components */
        public ?\FHIRNutritionOrderOralDiet $oralDiet = null,
        /** @var array<FHIRNutritionOrderSupplement> supplement Supplement components */
        public array $supplement = [],
        /** @var FHIRNutritionOrderEnteralFormula|null enteralFormula Enteral formula components */
        public ?\FHIRNutritionOrderEnteralFormula $enteralFormula = null,
        /** @var array<FHIRAnnotation> note Comments */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
