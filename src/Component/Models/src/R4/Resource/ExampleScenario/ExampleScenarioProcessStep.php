<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description Each step of the process.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step', fhirVersion: 'R4')]
class ExampleScenarioProcessStep extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<ExampleScenarioProcess> process Nested process */
        public array $process = [],
        /** @var bool|null pause If there is a pause in the flow */
        public ?bool $pause = null,
        /** @var ExampleScenarioProcessStepOperation|null operation Each interaction or action */
        public ?ExampleScenarioProcessStepOperation $operation = null,
        /** @var array<ExampleScenarioProcessStepAlternative> alternative Alternate non-typical step action */
        public array $alternative = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
