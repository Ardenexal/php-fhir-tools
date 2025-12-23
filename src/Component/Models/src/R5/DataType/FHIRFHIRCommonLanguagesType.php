<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRCommonLanguages
 * @description Code type wrapper for FHIRCommonLanguages enum
 */
class FHIRFHIRCommonLanguagesType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRCommonLanguages|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRCommonLanguages|string|null $value = null,
	) {
	}
}
