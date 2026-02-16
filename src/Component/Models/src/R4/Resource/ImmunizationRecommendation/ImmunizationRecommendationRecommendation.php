<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImmunizationRecommendation;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Vaccine administration recommendations.
 */
#[FHIRBackboneElement(
    parentResource: 'ImmunizationRecommendation',
    elementPath: 'ImmunizationRecommendation.recommendation',
    fhirVersion: 'R4',
)]
class ImmunizationRecommendationRecommendation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> vaccineCode Vaccine  or vaccine group recommendation applies to */
        public array $vaccineCode = [],
        /** @var CodeableConcept|null targetDisease Disease to be immunized against */
        public ?CodeableConcept $targetDisease = null,
        /** @var array<CodeableConcept> contraindicatedVaccineCode Vaccine which is contraindicated to fulfill the recommendation */
        public array $contraindicatedVaccineCode = [],
        /** @var CodeableConcept|null forecastStatus Vaccine recommendation status */
        #[NotBlank]
        public ?CodeableConcept $forecastStatus = null,
        /** @var array<CodeableConcept> forecastReason Vaccine administration status reason */
        public array $forecastReason = [],
        /** @var array<ImmunizationRecommendationRecommendationDateCriterion> dateCriterion Dates governing proposed immunization */
        public array $dateCriterion = [],
        /** @var StringPrimitive|string|null description Protocol details */
        public StringPrimitive|string|null $description = null,
        /** @var StringPrimitive|string|null series Name of vaccination series */
        public StringPrimitive|string|null $series = null,
        /** @var PositiveIntPrimitive|StringPrimitive|string|null doseNumberX Recommended dose number within series */
        public PositiveIntPrimitive|StringPrimitive|string|null $doseNumberX = null,
        /** @var PositiveIntPrimitive|StringPrimitive|string|null seriesDosesX Recommended number of doses for immunity */
        public PositiveIntPrimitive|StringPrimitive|string|null $seriesDosesX = null,
        /** @var array<Reference> supportingImmunization Past immunizations supporting recommendation */
        public array $supportingImmunization = [],
        /** @var array<Reference> supportingPatientInformation Patient observations supporting recommendation */
        public array $supportingPatientInformation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
