<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRTypeRestfulInteraction
 * @description Code type wrapper for FHIRTypeRestfulInteraction enum
 */
class FHIRTypeRestfulInteractionType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTypeRestfulInteraction|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTypeRestfulInteraction|string|null $value = null,
	) {
	}
}
