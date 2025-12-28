<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A population criteria for the measure.
 */
#[FHIRBackboneElement(parentResource: 'Measure', elementPath: 'Measure.group.population', fhirVersion: 'R4')]
class FHIRMeasureGroupPopulation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code initial-population | numerator | numerator-exclusion | denominator | denominator-exclusion | denominator-exception | measure-population | measure-population-exclusion | measure-observation */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRString|string|null description The human readable description of this population criteria */
        public \FHIRString|string|null $description = null,
        /** @var FHIRExpression|null criteria The criteria that defines this population */
        #[NotBlank]
        public ?\FHIRExpression $criteria = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
