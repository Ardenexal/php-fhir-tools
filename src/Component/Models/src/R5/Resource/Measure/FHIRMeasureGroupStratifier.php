<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description The stratifier criteria for the measure report, specified as either the name of a valid CQL expression defined within a referenced library or a valid FHIR Resource Path.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Measure', elementPath: 'Measure.group.stratifier', fhirVersion: 'R5')]
class FHIRMeasureGroupStratifier extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null linkId Unique id for stratifier in measure */
        public FHIRString|string|null $linkId = null,
        /** @var FHIRCodeableConcept|null code Meaning of the stratifier */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRMarkdown|null description The human readable description of this stratifier */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRExpression|null criteria How the measure should be stratified */
        public ?FHIRExpression $criteria = null,
        /** @var FHIRReference|null groupDefinition A group resource that defines this population */
        public ?FHIRReference $groupDefinition = null,
        /** @var array<FHIRMeasureGroupStratifierComponent> component Stratifier criteria component for the measure */
        public array $component = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
