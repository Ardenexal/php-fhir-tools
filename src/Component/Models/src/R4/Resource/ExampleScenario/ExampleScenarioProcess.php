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
 * @description Each major process - a group of operations.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process', fhirVersion: 'R4')]
class ExampleScenarioProcess extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null title The diagram title of the group of operations */
        #[NotBlank]
        public StringPrimitive|string|null $title = null,
        /** @var MarkdownPrimitive|null description A longer description of the group of operations */
        public ?MarkdownPrimitive $description = null,
        /** @var MarkdownPrimitive|null preConditions Description of initial status before the process starts */
        public ?MarkdownPrimitive $preConditions = null,
        /** @var MarkdownPrimitive|null postConditions Description of final status after the process ends */
        public ?MarkdownPrimitive $postConditions = null,
        /** @var array<ExampleScenarioProcessStep> step Each step of the process */
        public array $step = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
