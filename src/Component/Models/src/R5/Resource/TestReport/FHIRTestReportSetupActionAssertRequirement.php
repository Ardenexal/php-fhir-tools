<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Links or references providing traceability to the testing requirements for this assert.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.setup.action.assert.requirement', fhirVersion: 'R5')]
class FHIRTestReportSetupActionAssertRequirement extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical linkX Link or reference to the testing requirement */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical|null $linkX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
