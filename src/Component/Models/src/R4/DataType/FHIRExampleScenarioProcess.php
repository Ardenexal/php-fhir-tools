<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ExampleScenario.process
 * @description Each major process - a group of operations.
 */
class FHIRExampleScenarioProcess extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string title The diagram title of the group of operations */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown description A longer description of the group of operations */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown preConditions Description of initial status before the process starts */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown $preConditions = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown postConditions Description of final status after the process ends */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown $postConditions = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExampleScenarioProcessStep> step Each step of the process */
		public array $step = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
