<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRInventoryItemStatusCodes
 * @description Code type wrapper for FHIRInventoryItemStatusCodes enum
 */
class FHIRInventoryItemStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRInventoryItemStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRInventoryItemStatusCodes|string|null $value = null,
	) {
	}
}
