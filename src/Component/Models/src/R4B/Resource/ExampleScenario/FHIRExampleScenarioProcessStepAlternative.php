<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates an alternative step that can be taken instead of the operations on the base step in exceptional/atypical circumstances.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step.alternative', fhirVersion: 'R4B')]
class FHIRExampleScenarioProcessStepAlternative extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null title Label for alternative */
        #[NotBlank]
        public \FHIRString|string|null $title = null,
        /** @var FHIRMarkdown|null description A human-readable description of each option */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRExampleScenarioProcessStep> step What happens in each alternative option */
        public array $step = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
