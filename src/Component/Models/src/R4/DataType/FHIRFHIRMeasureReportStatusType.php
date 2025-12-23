<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRMeasureReportStatus
 * @description Code type wrapper for FHIRMeasureReportStatus enum
 */
class FHIRFHIRMeasureReportStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMeasureReportStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMeasureReportStatus|string|null $value = null,
	) {
	}
}
