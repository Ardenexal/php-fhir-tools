<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRWeekOfMonth
 * @description Code type wrapper for FHIRWeekOfMonth enum
 */
class FHIRWeekOfMonthType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRWeekOfMonth|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRWeekOfMonth|string|null $value = null,
	) {
	}
}
