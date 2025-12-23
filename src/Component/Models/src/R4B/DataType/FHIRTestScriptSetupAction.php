<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element TestScript.setup.action
 * @description Action would contain either an operation or an assertion.
 */
class FHIRTestScriptSetupAction extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTestScriptSetupActionOperation operation The setup operation to perform */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTestScriptSetupActionOperation $operation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTestScriptSetupActionAssert assert The assertion to perform */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTestScriptSetupActionAssert $assert = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
