<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRSystemRestfulInteraction
 * @description Code type wrapper for FHIRSystemRestfulInteraction enum
 */
class FHIRFHIRSystemRestfulInteractionType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSystemRestfulInteraction|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSystemRestfulInteraction|string|null $value = null,
	) {
	}
}
