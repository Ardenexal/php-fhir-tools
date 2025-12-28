<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates an alternative step that can be taken instead of the sub-process, scenario or operation.  E.g. to represent non-happy-path/exceptional/atypical circumstances.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step.alternative', fhirVersion: 'R5')]
class FHIRExampleScenarioProcessStepAlternative extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
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
        /** @var FHIRMarkdown|null description Human-readable description of option */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRExampleScenarioProcessStep> step Alternative action(s) */
        public array $step = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
