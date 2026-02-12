<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario;

/**
 * @description Each major process - a group of operations.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process', fhirVersion: 'R4')]
class ExampleScenarioProcess extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string title The diagram title of the group of operations */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive description A longer description of the group of operations */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive preConditions Description of initial status before the process starts */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $preConditions = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive postConditions Description of final status after the process ends */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $postConditions = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario\ExampleScenarioProcessStep> step Each step of the process */
		public array $step = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
