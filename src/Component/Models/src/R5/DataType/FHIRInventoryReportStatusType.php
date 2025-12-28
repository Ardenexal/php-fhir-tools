<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRInventoryReportStatus
 * @description Code type wrapper for FHIRInventoryReportStatus enum
 */
class FHIRInventoryReportStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRInventoryReportStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRInventoryReportStatus|string|null $value = null,
	) {
	}
}
