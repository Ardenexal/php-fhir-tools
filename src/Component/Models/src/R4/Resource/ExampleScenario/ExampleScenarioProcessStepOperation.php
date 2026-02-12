<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario;

/**
 * @description Each interaction or action.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step.operation', fhirVersion: 'R4')]
class ExampleScenarioProcessStepOperation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string number The sequential number of the interaction */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $number = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string type The type of operation - CRUD */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name The human-friendly name of the interaction */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string initiator Who starts the transaction */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $initiator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string receiver Who receives the transaction */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $receiver = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive description A comment to be inserted in the diagram */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $description = null,
		/** @var null|bool initiatorActive Whether the initiator is deactivated right after the transaction */
		public ?bool $initiatorActive = null,
		/** @var null|bool receiverActive Whether the receiver is deactivated right after the transaction */
		public ?bool $receiverActive = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario\ExampleScenarioInstanceContainedInstance request Each resource instance used by the initiator */
		public ?ExampleScenarioInstanceContainedInstance $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario\ExampleScenarioInstanceContainedInstance response Each resource instance used by the responder */
		public ?ExampleScenarioInstanceContainedInstance $response = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
