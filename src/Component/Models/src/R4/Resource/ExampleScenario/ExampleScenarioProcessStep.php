<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario;

/**
 * @description Each step of the process.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step', fhirVersion: 'R4')]
class ExampleScenarioProcessStep extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario\ExampleScenarioProcess> process Nested process */
		public array $process = [],
		/** @var null|bool pause If there is a pause in the flow */
		public ?bool $pause = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario\ExampleScenarioProcessStepOperation operation Each interaction or action */
		public ?ExampleScenarioProcessStepOperation $operation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExampleScenario\ExampleScenarioProcessStepAlternative> alternative Alternate non-typical step action */
		public array $alternative = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
