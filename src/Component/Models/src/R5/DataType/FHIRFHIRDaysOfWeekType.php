<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRDaysOfWeek
 * @description Code type wrapper for FHIRDaysOfWeek enum
 */
class FHIRFHIRDaysOfWeekType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDaysOfWeek|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDaysOfWeek|string|null $value = null,
	) {
	}
}
