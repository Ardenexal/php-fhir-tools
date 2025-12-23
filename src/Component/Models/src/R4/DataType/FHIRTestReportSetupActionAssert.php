<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element TestReport.setup.action.assert
 * @description The results of the assertion performed on the previous operations.
 */
class FHIRTestReportSetupActionAssert extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTestReportActionResultType result pass | skip | fail | warning | error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTestReportActionResultType $result = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown message A message associated with the result */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown $message = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string detail A link to further details on the result */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $detail = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
