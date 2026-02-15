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
 * @description Each interaction or action.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step.operation', fhirVersion: 'R4')]
class ExampleScenarioProcessStepOperation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null number The sequential number of the interaction */
        #[NotBlank]
        public StringPrimitive|string|null $number = null,
        /** @var StringPrimitive|string|null type The type of operation - CRUD */
        public StringPrimitive|string|null $type = null,
        /** @var StringPrimitive|string|null name The human-friendly name of the interaction */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null initiator Who starts the transaction */
        public StringPrimitive|string|null $initiator = null,
        /** @var StringPrimitive|string|null receiver Who receives the transaction */
        public StringPrimitive|string|null $receiver = null,
        /** @var MarkdownPrimitive|null description A comment to be inserted in the diagram */
        public ?MarkdownPrimitive $description = null,
        /** @var bool|null initiatorActive Whether the initiator is deactivated right after the transaction */
        public ?bool $initiatorActive = null,
        /** @var bool|null receiverActive Whether the receiver is deactivated right after the transaction */
        public ?bool $receiverActive = null,
        /** @var ExampleScenarioInstanceContainedInstance|null request Each resource instance used by the initiator */
        public ?ExampleScenarioInstanceContainedInstance $request = null,
        /** @var ExampleScenarioInstanceContainedInstance|null response Each resource instance used by the responder */
        public ?ExampleScenarioInstanceContainedInstance $response = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
