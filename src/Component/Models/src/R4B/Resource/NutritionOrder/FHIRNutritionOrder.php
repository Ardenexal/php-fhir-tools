<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/NutritionOrder
 * @description A request to supply a diet, formula feeding (enteral) or oral nutritional supplement to a patient/resident.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'NutritionOrder',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/NutritionOrder',
	fhirVersion: 'R4B',
)]
class FHIRNutritionOrder extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Identifiers assigned to this order */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
		public array $instantiatesCanonical = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri> instantiatesUri Instantiates external protocol or definition */
		public array $instantiatesUri = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri> instantiates Instantiates protocol or definition */
		public array $instantiates = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRequestStatusType status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRRequestStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRequestIntentType intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRRequestIntentType $intent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference patient The person who requires the diet, formula or nutritional supplement */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference encounter The encounter associated with this nutrition order */
		public ?FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime dateTime Date and time the nutrition order was requested */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDateTime $dateTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference orderer Who ordered the diet, formula or nutritional supplement */
		public ?FHIRReference $orderer = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> allergyIntolerance List of the patient's food and nutrition-related allergies and intolerances */
		public array $allergyIntolerance = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> foodPreferenceModifier Order-specific modifier about the type of food that should be given */
		public array $foodPreferenceModifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> excludeFoodModifier Order-specific modifier about the type of food that should not be given */
		public array $excludeFoodModifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNutritionOrderOralDiet oralDiet Oral diet components */
		public ?FHIRNutritionOrderOralDiet $oralDiet = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNutritionOrderSupplement> supplement Supplement components */
		public array $supplement = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNutritionOrderEnteralFormula enteralFormula Enteral formula components */
		public ?FHIRNutritionOrderEnteralFormula $enteralFormula = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAnnotation> note Comments */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
