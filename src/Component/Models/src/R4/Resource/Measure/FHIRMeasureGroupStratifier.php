<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExpression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description The stratifier criteria for the measure report, specified as either the name of a valid CQL expression defined within a referenced library or a valid FHIR Resource Path.
 */
#[FHIRBackboneElement(parentResource: 'Measure', elementPath: 'Measure.group.stratifier', fhirVersion: 'R4')]
class FHIRMeasureGroupStratifier extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Meaning of the stratifier */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRString|string|null description The human readable description of this stratifier */
        public FHIRString|string|null $description = null,
        /** @var FHIRExpression|null criteria How the measure should be stratified */
        public ?FHIRExpression $criteria = null,
        /** @var array<FHIRMeasureGroupStratifierComponent> component Stratifier criteria component for the measure */
        public array $component = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
