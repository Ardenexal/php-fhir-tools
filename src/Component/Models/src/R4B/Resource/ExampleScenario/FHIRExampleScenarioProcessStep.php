<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Each step of the process.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step', fhirVersion: 'R4B')]
class FHIRExampleScenarioProcessStep extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRExampleScenarioProcess> process Nested process */
        public array $process = [],
        /** @var FHIRBoolean|null pause If there is a pause in the flow */
        public ?\FHIRBoolean $pause = null,
        /** @var FHIRExampleScenarioProcessStepOperation|null operation Each interaction or action */
        public ?\FHIRExampleScenarioProcessStepOperation $operation = null,
        /** @var array<FHIRExampleScenarioProcessStepAlternative> alternative Alternate non-typical step action */
        public array $alternative = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
