<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/NutritionOrder
 *
 * @description A request to supply a diet, formula feeding (enteral) or oral nutritional supplement to a patient/resident.
 */
#[FhirResource(
    type: 'NutritionOrder',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/NutritionOrder',
    fhirVersion: 'R5',
)]
class FHIRNutritionOrder extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Identifiers assigned to this order */
        public array $identifier = [],
        /** @var array<FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<FHIRUri> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<FHIRUri> instantiates Instantiates protocol or definition */
        public array $instantiates = [],
        /** @var array<FHIRReference> basedOn What this order fulfills */
        public array $basedOn = [],
        /** @var FHIRIdentifier|null groupIdentifier Composite Request ID */
        public ?FHIRIdentifier $groupIdentifier = null,
        /** @var FHIRRequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIRRequestStatusType $status = null,
        /** @var FHIRRequestIntentType|null intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?FHIRRequestIntentType $intent = null,
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var FHIRReference|null subject Who requires the diet, formula or nutritional supplement */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter The encounter associated with this nutrition order */
        public ?FHIRReference $encounter = null,
        /** @var array<FHIRReference> supportingInformation Information to support fulfilling of the nutrition order */
        public array $supportingInformation = [],
        /** @var FHIRDateTime|null dateTime Date and time the nutrition order was requested */
        #[NotBlank]
        public ?FHIRDateTime $dateTime = null,
        /** @var FHIRReference|null orderer Who ordered the diet, formula or nutritional supplement */
        public ?FHIRReference $orderer = null,
        /** @var array<FHIRCodeableReference> performer Who is desired to perform the administration of what is being ordered */
        public array $performer = [],
        /** @var array<FHIRReference> allergyIntolerance List of the patient's food and nutrition-related allergies and intolerances */
        public array $allergyIntolerance = [],
        /** @var array<FHIRCodeableConcept> foodPreferenceModifier Order-specific modifier about the type of food that should be given */
        public array $foodPreferenceModifier = [],
        /** @var array<FHIRCodeableConcept> excludeFoodModifier Order-specific modifier about the type of food that should not be given */
        public array $excludeFoodModifier = [],
        /** @var FHIRBoolean|null outsideFoodAllowed Capture when a food item is brought in by the patient and/or family */
        public ?FHIRBoolean $outsideFoodAllowed = null,
        /** @var FHIRNutritionOrderOralDiet|null oralDiet Oral diet components */
        public ?FHIRNutritionOrderOralDiet $oralDiet = null,
        /** @var array<FHIRNutritionOrderSupplement> supplement Supplement components */
        public array $supplement = [],
        /** @var FHIRNutritionOrderEnteralFormula|null enteralFormula Enteral formula components */
        public ?FHIRNutritionOrderEnteralFormula $enteralFormula = null,
        /** @var array<FHIRAnnotation> note Comments */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
