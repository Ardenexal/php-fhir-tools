<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates an alternative step that can be taken instead of the operations on the base step in exceptional/atypical circumstances.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step.alternative', fhirVersion: 'R4')]
class ExampleScenarioProcessStepAlternative extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null title Label for alternative */
        #[NotBlank]
        public StringPrimitive|string|null $title = null,
        /** @var MarkdownPrimitive|null description A human-readable description of each option */
        public ?MarkdownPrimitive $description = null,
        /** @var array<ExampleScenarioProcessStep> step What happens in each alternative option */
        public array $step = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
