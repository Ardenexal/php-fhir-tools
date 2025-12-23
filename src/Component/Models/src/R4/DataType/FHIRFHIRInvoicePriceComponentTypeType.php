<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRInvoicePriceComponentType
 * @description Code type wrapper for FHIRInvoicePriceComponentType enum
 */
class FHIRFHIRInvoicePriceComponentTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRInvoicePriceComponentType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRInvoicePriceComponentType|string|null $value = null,
	) {
	}
}
