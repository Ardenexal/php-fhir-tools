<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRInventoryReportStatus
 * @description Code type wrapper for FHIRInventoryReportStatus enum
 */
class FHIRFHIRInventoryReportStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRInventoryReportStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRInventoryReportStatus|string|null $value = null,
	) {
	}
}
