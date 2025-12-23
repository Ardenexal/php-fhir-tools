<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRInventoryItemStatusCodes
 * @description Code type wrapper for FHIRInventoryItemStatusCodes enum
 */
class FHIRFHIRInventoryItemStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRInventoryItemStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRInventoryItemStatusCodes|string|null $value = null,
	) {
	}
}
