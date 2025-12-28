<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRSystemRestfulInteraction
 * @description Code type wrapper for FHIRSystemRestfulInteraction enum
 */
class FHIRSystemRestfulInteractionType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSystemRestfulInteraction|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSystemRestfulInteraction|string|null $value = null,
	) {
	}
}
