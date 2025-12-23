<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ExampleScenario.process.step
 * @description A significant action that occurs as part of the process.
 */
class FHIRExampleScenarioProcessStep extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string number Sequential number of the step */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $number = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioProcess process Step is nested process */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioProcess $process = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical workflow Step is nested workflow */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $workflow = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioProcessStepOperation operation Step is simple action */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioProcessStepOperation $operation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioProcessStepAlternative> alternative Alternate non-typical step action */
		public array $alternative = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean pause Pause in the flow? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $pause = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
