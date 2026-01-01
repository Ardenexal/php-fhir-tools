<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFHIRTypesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description A group of population criteria for the measure.
 */
#[FHIRBackboneElement(parentResource: 'Measure', elementPath: 'Measure.group', fhirVersion: 'R5')]
class FHIRMeasureGroup extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null linkId Unique id for group in measure */
        public FHIRString|string|null $linkId = null,
        /** @var FHIRCodeableConcept|null code Meaning of the group */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRMarkdown|null description Summary description */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRCodeableConcept> type process | outcome | structure | patient-reported-outcome | composite */
        public array $type = [],
        /** @var FHIRCodeableConcept|FHIRReference|null subjectX E.g. Patient, Practitioner, RelatedPerson, Organization, Location, Device */
        public FHIRCodeableConcept|FHIRReference|null $subjectX = null,
        /** @var FHIRFHIRTypesType|null basis Population basis */
        public ?FHIRFHIRTypesType $basis = null,
        /** @var FHIRCodeableConcept|null scoring proportion | ratio | continuous-variable | cohort */
        public ?FHIRCodeableConcept $scoring = null,
        /** @var FHIRCodeableConcept|null scoringUnit What units? */
        public ?FHIRCodeableConcept $scoringUnit = null,
        /** @var FHIRMarkdown|null rateAggregation How is rate aggregation performed for this measure */
        public ?FHIRMarkdown $rateAggregation = null,
        /** @var FHIRCodeableConcept|null improvementNotation increase | decrease */
        public ?FHIRCodeableConcept $improvementNotation = null,
        /** @var array<FHIRCanonical> library Logic used by the measure group */
        public array $library = [],
        /** @var array<FHIRMeasureGroupPopulation> population Population criteria */
        public array $population = [],
        /** @var array<FHIRMeasureGroupStratifier> stratifier Stratifier criteria for the measure */
        public array $stratifier = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
