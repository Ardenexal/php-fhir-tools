<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A group of operations that represents a significant step within a scenario.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process', fhirVersion: 'R5')]
class FHIRExampleScenarioProcess extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null title Label for procss */
        #[NotBlank]
        public FHIRString|string|null $title = null,
        /** @var FHIRMarkdown|null description Human-friendly description of the process */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRMarkdown|null preConditions Status before process starts */
        public ?FHIRMarkdown $preConditions = null,
        /** @var FHIRMarkdown|null postConditions Status after successful completion */
        public ?FHIRMarkdown $postConditions = null,
        /** @var array<FHIRExampleScenarioProcessStep> step Event within of the process */
        public array $step = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
