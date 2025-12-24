<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Each interaction or action.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step.operation', fhirVersion: 'R4')]
class FHIRExampleScenarioProcessStepOperation extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null number The sequential number of the interaction */
        #[NotBlank]
        public FHIRString|string|null $number = null,
        /** @var FHIRString|string|null type The type of operation - CRUD */
        public FHIRString|string|null $type = null,
        /** @var FHIRString|string|null name The human-friendly name of the interaction */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null initiator Who starts the transaction */
        public FHIRString|string|null $initiator = null,
        /** @var FHIRString|string|null receiver Who receives the transaction */
        public FHIRString|string|null $receiver = null,
        /** @var FHIRMarkdown|null description A comment to be inserted in the diagram */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRBoolean|null initiatorActive Whether the initiator is deactivated right after the transaction */
        public ?FHIRBoolean $initiatorActive = null,
        /** @var FHIRBoolean|null receiverActive Whether the receiver is deactivated right after the transaction */
        public ?FHIRBoolean $receiverActive = null,
        /** @var FHIRExampleScenarioInstanceContainedInstance|null request Each resource instance used by the initiator */
        public ?FHIRExampleScenarioInstanceContainedInstance $request = null,
        /** @var FHIRExampleScenarioInstanceContainedInstance|null response Each resource instance used by the responder */
        public ?FHIRExampleScenarioInstanceContainedInstance $response = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
