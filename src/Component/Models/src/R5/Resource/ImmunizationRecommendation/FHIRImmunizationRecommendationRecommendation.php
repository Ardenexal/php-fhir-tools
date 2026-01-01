<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Vaccine administration recommendations.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'ImmunizationRecommendation',
	elementPath: 'ImmunizationRecommendation.recommendation',
	fhirVersion: 'R5',
)]
class FHIRImmunizationRecommendationRecommendation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> vaccineCode Vaccine  or vaccine group recommendation applies to */
		public array $vaccineCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> targetDisease Disease to be immunized against */
		public array $targetDisease = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> contraindicatedVaccineCode Vaccine which is contraindicated to fulfill the recommendation */
		public array $contraindicatedVaccineCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept forecastStatus Vaccine recommendation status */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $forecastStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> forecastReason Vaccine administration status reason */
		public array $forecastReason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImmunizationRecommendationRecommendationDateCriterion> dateCriterion Dates governing proposed immunization */
		public array $dateCriterion = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description Protocol details */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string series Name of vaccination series */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $series = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string doseNumber Recommended dose number within series */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $doseNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string seriesDoses Recommended number of doses for immunity */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $seriesDoses = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> supportingImmunization Past immunizations supporting recommendation */
		public array $supportingImmunization = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> supportingPatientInformation Patient observations supporting recommendation */
		public array $supportingPatientInformation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
