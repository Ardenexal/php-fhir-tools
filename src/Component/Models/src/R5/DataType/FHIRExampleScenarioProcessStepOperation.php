<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ExampleScenario.process.step.operation
 * @description The step represents a single operation invoked on receiver by sender.
 */
class FHIRExampleScenarioProcessStepOperation extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding type Kind of action */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string title Label for step */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string initiator Who starts the operation */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $initiator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string receiver Who receives the operation */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $receiver = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Human-friendly description of the operation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean initiatorActive Initiator stays active? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $initiatorActive = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean receiverActive Receiver stays active? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $receiverActive = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioInstanceContainedInstance request Instance transmitted on invocation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioInstanceContainedInstance $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioInstanceContainedInstance response Instance transmitted on invocation response */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioInstanceContainedInstance $response = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
