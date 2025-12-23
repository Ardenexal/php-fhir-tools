<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/NutritionOrder
 * @description A request to supply a diet, formula feeding (enteral) or oral nutritional supplement to a patient/resident.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'NutritionOrder',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/NutritionOrder',
	fhirVersion: 'R5',
)]
class FHIRNutritionOrder extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Identifiers assigned to this order */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
		public array $instantiatesCanonical = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri> instantiatesUri Instantiates external protocol or definition */
		public array $instantiatesUri = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri> instantiates Instantiates protocol or definition */
		public array $instantiates = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> basedOn What this order fulfills */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier groupIdentifier Composite Request ID */
		public ?FHIRIdentifier $groupIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestStatusType status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRRequestStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestIntentType intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRRequestIntentType $intent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?FHIRRequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subject Who requires the diet, formula or nutritional supplement */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference encounter The encounter associated with this nutrition order */
		public ?FHIRReference $encounter = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> supportingInformation Information to support fulfilling of the nutrition order */
		public array $supportingInformation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime dateTime Date and time the nutrition order was requested */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDateTime $dateTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference orderer Who ordered the diet, formula or nutritional supplement */
		public ?FHIRReference $orderer = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> performer Who is desired to perform the administration of what is being ordered */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> allergyIntolerance List of the patient's food and nutrition-related allergies and intolerances */
		public array $allergyIntolerance = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> foodPreferenceModifier Order-specific modifier about the type of food that should be given */
		public array $foodPreferenceModifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> excludeFoodModifier Order-specific modifier about the type of food that should not be given */
		public array $excludeFoodModifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean outsideFoodAllowed Capture when a food item is brought in by the patient and/or family */
		public ?FHIRBoolean $outsideFoodAllowed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNutritionOrderOralDiet oralDiet Oral diet components */
		public ?FHIRNutritionOrderOralDiet $oralDiet = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNutritionOrderSupplement> supplement Supplement components */
		public array $supplement = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNutritionOrderEnteralFormula enteralFormula Enteral formula components */
		public ?FHIRNutritionOrderEnteralFormula $enteralFormula = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Comments */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
