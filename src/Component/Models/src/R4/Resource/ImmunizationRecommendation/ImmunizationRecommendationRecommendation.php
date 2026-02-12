<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImmunizationRecommendation;

/**
 * @description Vaccine administration recommendations.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'ImmunizationRecommendation',
	elementPath: 'ImmunizationRecommendation.recommendation',
	fhirVersion: 'R4',
)]
class ImmunizationRecommendationRecommendation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> vaccineCode Vaccine  or vaccine group recommendation applies to */
		public array $vaccineCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept targetDisease Disease to be immunized against */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $targetDisease = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> contraindicatedVaccineCode Vaccine which is contraindicated to fulfill the recommendation */
		public array $contraindicatedVaccineCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept forecastStatus Vaccine recommendation status */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $forecastStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> forecastReason Vaccine administration status reason */
		public array $forecastReason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ImmunizationRecommendation\ImmunizationRecommendationRecommendationDateCriterion> dateCriterion Dates governing proposed immunization */
		public array $dateCriterion = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Protocol details */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string series Name of vaccination series */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $series = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string doseNumberX Recommended dose number within series */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $doseNumberX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string seriesDosesX Recommended number of doses for immunity */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $seriesDosesX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> supportingImmunization Past immunizations supporting recommendation */
		public array $supportingImmunization = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> supportingPatientInformation Patient observations supporting recommendation */
		public array $supportingPatientInformation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
