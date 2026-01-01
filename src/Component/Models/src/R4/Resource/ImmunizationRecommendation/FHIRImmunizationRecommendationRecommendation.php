<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Vaccine administration recommendations.
 */
#[FHIRBackboneElement(
    parentResource: 'ImmunizationRecommendation',
    elementPath: 'ImmunizationRecommendation.recommendation',
    fhirVersion: 'R4',
)]
class FHIRImmunizationRecommendationRecommendation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableConcept> vaccineCode Vaccine  or vaccine group recommendation applies to */
        public array $vaccineCode = [],
        /** @var FHIRCodeableConcept|null targetDisease Disease to be immunized against */
        public ?FHIRCodeableConcept $targetDisease = null,
        /** @var array<FHIRCodeableConcept> contraindicatedVaccineCode Vaccine which is contraindicated to fulfill the recommendation */
        public array $contraindicatedVaccineCode = [],
        /** @var FHIRCodeableConcept|null forecastStatus Vaccine recommendation status */
        #[NotBlank]
        public ?FHIRCodeableConcept $forecastStatus = null,
        /** @var array<FHIRCodeableConcept> forecastReason Vaccine administration status reason */
        public array $forecastReason = [],
        /** @var array<FHIRImmunizationRecommendationRecommendationDateCriterion> dateCriterion Dates governing proposed immunization */
        public array $dateCriterion = [],
        /** @var FHIRString|string|null description Protocol details */
        public FHIRString|string|null $description = null,
        /** @var FHIRString|string|null series Name of vaccination series */
        public FHIRString|string|null $series = null,
        /** @var FHIRPositiveInt|FHIRString|string|null doseNumberX Recommended dose number within series */
        public FHIRPositiveInt|FHIRString|string|null $doseNumberX = null,
        /** @var FHIRPositiveInt|FHIRString|string|null seriesDosesX Recommended number of doses for immunity */
        public FHIRPositiveInt|FHIRString|string|null $seriesDosesX = null,
        /** @var array<FHIRReference> supportingImmunization Past immunizations supporting recommendation */
        public array $supportingImmunization = [],
        /** @var array<FHIRReference> supportingPatientInformation Patient observations supporting recommendation */
        public array $supportingPatientInformation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
