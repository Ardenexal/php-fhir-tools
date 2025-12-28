<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Vaccine date recommendations.  For example, earliest date to administer, latest date to administer, etc.
 */
#[FHIRBackboneElement(
    parentResource: 'ImmunizationRecommendation',
    elementPath: 'ImmunizationRecommendation.recommendation.dateCriterion',
    fhirVersion: 'R4B',
)]
class FHIRImmunizationRecommendationRecommendationDateCriterion extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Type of date */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRDateTime|null value Recommended date */
        #[NotBlank]
        public ?\FHIRDateTime $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
