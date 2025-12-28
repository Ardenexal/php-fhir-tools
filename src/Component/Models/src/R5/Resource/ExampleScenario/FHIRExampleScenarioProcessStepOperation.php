<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The step represents a single operation invoked on receiver by sender.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step.operation', fhirVersion: 'R5')]
class FHIRExampleScenarioProcessStepOperation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding type Kind of action */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string title Label for step */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string initiator Who starts the operation */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $initiator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string receiver Who receives the operation */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $receiver = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description Human-friendly description of the operation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean initiatorActive Initiator stays active? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $initiatorActive = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean receiverActive Receiver stays active? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $receiverActive = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioInstanceContainedInstance request Instance transmitted on invocation */
		public ?FHIRExampleScenarioInstanceContainedInstance $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioInstanceContainedInstance response Instance transmitted on invocation response */
		public ?FHIRExampleScenarioInstanceContainedInstance $response = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
