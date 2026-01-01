<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRTypeRestfulInteraction
 * @description Code type wrapper for FHIRTypeRestfulInteraction enum
 */
class FHIRTypeRestfulInteractionType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTypeRestfulInteraction|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
