<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport;

/**
 * @description The teardown action will only contain an operation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.teardown.action', fhirVersion: 'R4')]
class TestReportTeardownAction extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport\TestReportSetupActionOperation operation The teardown operation performed */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?TestReportSetupActionOperation $operation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
