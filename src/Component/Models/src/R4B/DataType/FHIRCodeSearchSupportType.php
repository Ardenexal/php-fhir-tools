<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRCodeSearchSupport
 * @description Code type wrapper for FHIRCodeSearchSupport enum
 */
class FHIRCodeSearchSupportType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCodeSearchSupport|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCodeSearchSupport|string|null $value = null,
	) {
	}
}
