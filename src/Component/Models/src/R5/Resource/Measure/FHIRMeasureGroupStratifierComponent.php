<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description A component of the stratifier criteria for the measure report, specified as either the name of a valid CQL expression defined within a referenced library or a valid FHIR Resource Path.
 */
#[FHIRBackboneElement(parentResource: 'Measure', elementPath: 'Measure.group.stratifier.component', fhirVersion: 'R5')]
class FHIRMeasureGroupStratifierComponent extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null linkId Unique id for stratifier component in measure */
        public \FHIRString|string|null $linkId = null,
        /** @var FHIRCodeableConcept|null code Meaning of the stratifier component */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRMarkdown|null description The human readable description of this stratifier component */
        public ?\FHIRMarkdown $description = null,
        /** @var FHIRExpression|null criteria Component of how the measure should be stratified */
        public ?\FHIRExpression $criteria = null,
        /** @var FHIRReference|null groupDefinition A group resource that defines this population */
        public ?\FHIRReference $groupDefinition = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
