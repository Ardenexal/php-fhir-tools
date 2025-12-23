<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRWeekOfMonth
 * @description Code type wrapper for FHIRWeekOfMonth enum
 */
class FHIRFHIRWeekOfMonthType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRWeekOfMonth|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRWeekOfMonth|string|null $value = null,
	) {
	}
}
