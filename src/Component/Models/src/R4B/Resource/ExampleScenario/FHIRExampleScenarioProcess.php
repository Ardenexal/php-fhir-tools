<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Each major process - a group of operations.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process', fhirVersion: 'R4B')]
class FHIRExampleScenarioProcess extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null title The diagram title of the group of operations */
        #[NotBlank]
        public FHIRString|string|null $title = null,
        /** @var FHIRMarkdown|null description A longer description of the group of operations */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRMarkdown|null preConditions Description of initial status before the process starts */
        public ?FHIRMarkdown $preConditions = null,
        /** @var FHIRMarkdown|null postConditions Description of final status after the process ends */
        public ?FHIRMarkdown $postConditions = null,
        /** @var array<FHIRExampleScenarioProcessStep> step Each step of the process */
        public array $step = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
