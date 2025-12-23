<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ImmunizationRecommendation.recommendation
 * @description Vaccine administration recommendations.
 */
class FHIRImmunizationRecommendationRecommendation extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> vaccineCode Vaccine  or vaccine group recommendation applies to */
		public array $vaccineCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept targetDisease Disease to be immunized against */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $targetDisease = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> contraindicatedVaccineCode Vaccine which is contraindicated to fulfill the recommendation */
		public array $contraindicatedVaccineCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept forecastStatus Vaccine recommendation status */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $forecastStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> forecastReason Vaccine administration status reason */
		public array $forecastReason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRImmunizationRecommendationRecommendationDateCriterion> dateCriterion Dates governing proposed immunization */
		public array $dateCriterion = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string description Protocol details */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string series Name of vaccination series */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $series = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string doseNumberX Recommended dose number within series */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $doseNumberX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string seriesDosesX Recommended number of doses for immunity */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $seriesDosesX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> supportingImmunization Past immunizations supporting recommendation */
		public array $supportingImmunization = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> supportingPatientInformation Patient observations supporting recommendation */
		public array $supportingPatientInformation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
