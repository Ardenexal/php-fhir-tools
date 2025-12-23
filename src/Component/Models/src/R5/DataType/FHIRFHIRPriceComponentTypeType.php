<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRPriceComponentType
 * @description Code type wrapper for FHIRPriceComponentType enum
 */
class FHIRFHIRPriceComponentTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRPriceComponentType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRPriceComponentType|string|null $value = null,
	) {
	}
}
