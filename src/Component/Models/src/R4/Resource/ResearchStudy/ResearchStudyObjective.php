<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ResearchStudy;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description A goal that the study is aiming to achieve in terms of a scientific question to be answered by the analysis of data collected during the study.
 */
#[FHIRBackboneElement(parentResource: 'ResearchStudy', elementPath: 'ResearchStudy.objective', fhirVersion: 'R4')]
class ResearchStudyObjective extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null name Label for the objective */
        public StringPrimitive|string|null $name = null,
        /** @var CodeableConcept|null type primary | secondary | exploratory */
        public ?CodeableConcept $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
