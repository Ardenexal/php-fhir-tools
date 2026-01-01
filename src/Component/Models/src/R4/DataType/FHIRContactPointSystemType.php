<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRContactPointSystem
 * @description Code type wrapper for FHIRContactPointSystem enum
 */
class FHIRContactPointSystemType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRContactPointSystem|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
