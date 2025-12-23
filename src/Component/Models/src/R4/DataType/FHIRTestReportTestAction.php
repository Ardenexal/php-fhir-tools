<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element TestReport.test.action
 * @description Action would contain either an operation or an assertion.
 */
class FHIRTestReportTestAction extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTestReportSetupActionOperation operation The operation performed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTestReportSetupActionOperation $operation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTestReportSetupActionAssert assert The assertion performed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTestReportSetupActionAssert $assert = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
