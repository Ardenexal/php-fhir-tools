<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRTestReportResult
 * @description Code type wrapper for FHIRTestReportResult enum
 */
class FHIRFHIRTestReportResultType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTestReportResult|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTestReportResult|string|null $value = null,
	) {
	}
}
