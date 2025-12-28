<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRCodeSystemContentMode
 * @description Code type wrapper for FHIRCodeSystemContentMode enum
 */
class FHIRCodeSystemContentModeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCodeSystemContentMode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCodeSystemContentMode|string|null $value = null,
	) {
	}
}
