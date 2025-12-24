<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description A goal that the study is aiming to achieve in terms of a scientific question to be answered by the analysis of data collected during the study.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ResearchStudy', elementPath: 'ResearchStudy.objective', fhirVersion: 'R5')]
class FHIRResearchStudyObjective extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Label for the objective */
        public FHIRString|string|null $name = null,
        /** @var FHIRCodeableConcept|null type primary | secondary | exploratory */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRMarkdown|null description Description of the objective */
        public ?FHIRMarkdown $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
