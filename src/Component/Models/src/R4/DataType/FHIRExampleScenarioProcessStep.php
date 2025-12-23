<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ExampleScenario.process.step
 * @description Each step of the process.
 */
class FHIRExampleScenarioProcessStep extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExampleScenarioProcess> process Nested process */
		public array $process = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean pause If there is a pause in the flow */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $pause = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExampleScenarioProcessStepOperation operation Each interaction or action */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExampleScenarioProcessStepOperation $operation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExampleScenarioProcessStepAlternative> alternative Alternate non-typical step action */
		public array $alternative = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
