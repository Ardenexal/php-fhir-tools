<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Evidence variable such as population, exposure, or outcome.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.variableDefinition', fhirVersion: 'R4B')]
class FHIREvidenceVariableDefinition extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRMarkdown|null description A text description or summary of the variable */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRAnnotation> note Footnotes and/or explanatory notes */
        public array $note = [],
        /** @var FHIRCodeableConcept|null variableRole population | subpopulation | exposure | referenceExposure | measuredVariable | confounder */
        #[NotBlank]
        public ?FHIRCodeableConcept $variableRole = null,
        /** @var FHIRReference|null observed Definition of the actual variable related to the statistic(s) */
        public ?FHIRReference $observed = null,
        /** @var FHIRReference|null intended Definition of the intended variable related to the Evidence */
        public ?FHIRReference $intended = null,
        /** @var FHIRCodeableConcept|null directnessMatch low | moderate | high | exact */
        public ?FHIRCodeableConcept $directnessMatch = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
