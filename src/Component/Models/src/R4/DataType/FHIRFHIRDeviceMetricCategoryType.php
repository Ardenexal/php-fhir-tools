<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRDeviceMetricCategory
 * @description Code type wrapper for FHIRDeviceMetricCategory enum
 */
class FHIRFHIRDeviceMetricCategoryType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceMetricCategory|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceMetricCategory|string|null $value = null,
	) {
	}
}
