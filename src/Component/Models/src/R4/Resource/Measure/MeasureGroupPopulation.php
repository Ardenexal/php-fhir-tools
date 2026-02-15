<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Measure;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A population criteria for the measure.
 */
#[FHIRBackboneElement(parentResource: 'Measure', elementPath: 'Measure.group.population', fhirVersion: 'R4')]
class MeasureGroupPopulation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code initial-population | numerator | numerator-exclusion | denominator | denominator-exclusion | denominator-exception | measure-population | measure-population-exclusion | measure-observation */
        public ?CodeableConcept $code = null,
        /** @var StringPrimitive|string|null description The human readable description of this population criteria */
        public StringPrimitive|string|null $description = null,
        /** @var Expression|null criteria The criteria that defines this population */
        #[NotBlank]
        public ?Expression $criteria = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
