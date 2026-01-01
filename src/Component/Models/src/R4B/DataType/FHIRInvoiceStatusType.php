<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRInvoiceStatus
 * @description Code type wrapper for FHIRInvoiceStatus enum
 */
class FHIRInvoiceStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRInvoiceStatus|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
