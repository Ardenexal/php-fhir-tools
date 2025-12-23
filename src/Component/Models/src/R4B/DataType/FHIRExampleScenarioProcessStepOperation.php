<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ExampleScenario.process.step.operation
 * @description Each interaction or action.
 */
class FHIRExampleScenarioProcessStepOperation extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string number The sequential number of the interaction */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $number = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string type The type of operation - CRUD */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string name The human-friendly name of the interaction */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string initiator Who starts the transaction */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $initiator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string receiver Who receives the transaction */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $receiver = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown description A comment to be inserted in the diagram */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean initiatorActive Whether the initiator is deactivated right after the transaction */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $initiatorActive = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean receiverActive Whether the receiver is deactivated right after the transaction */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $receiverActive = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExampleScenarioInstanceContainedInstance request Each resource instance used by the initiator */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExampleScenarioInstanceContainedInstance $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExampleScenarioInstanceContainedInstance response Each resource instance used by the responder */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExampleScenarioInstanceContainedInstance $response = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
