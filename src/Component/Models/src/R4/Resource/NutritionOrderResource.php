<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestIntentType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder\NutritionOrderEnteralFormula;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder\NutritionOrderOralDiet;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder\NutritionOrderSupplement;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/NutritionOrder',
    fhirVersion: 'R4',
)]
class NutritionOrderResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Identifiers assigned to this order */
        public array $identifier = [],
        /** @var array<CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<UriPrimitive> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<UriPrimitive> instantiates Instantiates protocol or definition */
        public array $instantiates = [],
        /** @var RequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?RequestStatusType $status = null,
        /** @var RequestIntentType|null intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?RequestIntentType $intent = null,
        /** @var Reference|null patient The person who requires the diet, formula or nutritional supplement */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var Reference|null encounter The encounter associated with this nutrition order */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|null dateTime Date and time the nutrition order was requested */
        #[NotBlank]
        public ?DateTimePrimitive $dateTime = null,
        /** @var Reference|null orderer Who ordered the diet, formula or nutritional supplement */
        public ?Reference $orderer = null,
        /** @var array<Reference> allergyIntolerance List of the patient's food and nutrition-related allergies and intolerances */
        public array $allergyIntolerance = [],
        /** @var array<CodeableConcept> foodPreferenceModifier Order-specific modifier about the type of food that should be given */
        public array $foodPreferenceModifier = [],
        /** @var array<CodeableConcept> excludeFoodModifier Order-specific modifier about the type of food that should not be given */
        public array $excludeFoodModifier = [],
        /** @var NutritionOrderOralDiet|null oralDiet Oral diet components */
        public ?NutritionOrderOralDiet $oralDiet = null,
        /** @var array<NutritionOrderSupplement> supplement Supplement components */
        public array $supplement = [],
        /** @var NutritionOrderEnteralFormula|null enteralFormula Enteral formula components */
        public ?NutritionOrderEnteralFormula $enteralFormula = null,
        /** @var array<Annotation> note Comments */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
