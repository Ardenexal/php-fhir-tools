<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A component of the stratifier criteria for the measure report, specified as either the name of a valid CQL expression defined within a referenced library or a valid FHIR Resource Path.
 */
#[FHIRBackboneElement(parentResource: 'Measure', elementPath: 'Measure.group.stratifier.component', fhirVersion: 'R4B')]
class FHIRMeasureGroupStratifierComponent extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Meaning of the stratifier component */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRString|string|null description The human readable description of this stratifier component */
        public \FHIRString|string|null $description = null,
        /** @var FHIRExpression|null criteria Component of how the measure should be stratified */
        #[NotBlank]
        public ?\FHIRExpression $criteria = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
