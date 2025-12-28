<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRAdverseEventActuality
 * @description Code type wrapper for FHIRAdverseEventActuality enum
 */
class FHIRAdverseEventActualityType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdverseEventActuality|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdverseEventActuality|string|null $value = null,
	) {
	}
}
