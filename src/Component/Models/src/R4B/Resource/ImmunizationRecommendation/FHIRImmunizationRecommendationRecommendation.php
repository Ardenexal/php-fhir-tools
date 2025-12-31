<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Vaccine administration recommendations.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'ImmunizationRecommendation',
	elementPath: 'ImmunizationRecommendation.recommendation',
	fhirVersion: 'R4B',
)]
class FHIRImmunizationRecommendationRecommendation extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> vaccineCode Vaccine  or vaccine group recommendation applies to */
		public array $vaccineCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept targetDisease Disease to be immunized against */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $targetDisease = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> contraindicatedVaccineCode Vaccine which is contraindicated to fulfill the recommendation */
		public array $contraindicatedVaccineCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept forecastStatus Vaccine recommendation status */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $forecastStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> forecastReason Vaccine administration status reason */
		public array $forecastReason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRImmunizationRecommendationRecommendationDateCriterion> dateCriterion Dates governing proposed immunization */
		public array $dateCriterion = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string description Protocol details */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string series Name of vaccination series */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $series = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string doseNumberX Recommended dose number within series */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $doseNumberX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string seriesDosesX Recommended number of doses for immunity */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $seriesDosesX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> supportingImmunization Past immunizations supporting recommendation */
		public array $supportingImmunization = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> supportingPatientInformation Patient observations supporting recommendation */
		public array $supportingPatientInformation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
