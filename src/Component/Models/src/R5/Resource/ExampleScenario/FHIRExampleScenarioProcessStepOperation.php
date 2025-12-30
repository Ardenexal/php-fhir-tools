<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The step represents a single operation invoked on receiver by sender.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step.operation', fhirVersion: 'R5')]
class FHIRExampleScenarioProcessStepOperation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCoding|null type Kind of action */
        public ?FHIRCoding $type = null,
        /** @var FHIRString|string|null title Label for step */
        #[NotBlank]
        public FHIRString|string|null $title = null,
        /** @var FHIRString|string|null initiator Who starts the operation */
        public FHIRString|string|null $initiator = null,
        /** @var FHIRString|string|null receiver Who receives the operation */
        public FHIRString|string|null $receiver = null,
        /** @var FHIRMarkdown|null description Human-friendly description of the operation */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRBoolean|null initiatorActive Initiator stays active? */
        public ?FHIRBoolean $initiatorActive = null,
        /** @var FHIRBoolean|null receiverActive Receiver stays active? */
        public ?FHIRBoolean $receiverActive = null,
        /** @var FHIRExampleScenarioInstanceContainedInstance|null request Instance transmitted on invocation */
        public ?FHIRExampleScenarioInstanceContainedInstance $request = null,
        /** @var FHIRExampleScenarioInstanceContainedInstance|null response Instance transmitted on invocation response */
        public ?FHIRExampleScenarioInstanceContainedInstance $response = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
