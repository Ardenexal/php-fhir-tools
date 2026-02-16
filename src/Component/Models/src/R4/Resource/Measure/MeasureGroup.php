<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Measure;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description A group of population criteria for the measure.
 */
#[FHIRBackboneElement(parentResource: 'Measure', elementPath: 'Measure.group', fhirVersion: 'R4')]
class MeasureGroup extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Meaning of the group */
        public ?CodeableConcept $code = null,
        /** @var StringPrimitive|string|null description Summary description */
        public StringPrimitive|string|null $description = null,
        /** @var array<MeasureGroupPopulation> population Population criteria */
        public array $population = [],
        /** @var array<MeasureGroupStratifier> stratifier Stratifier criteria for the measure */
        public array $stratifier = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
