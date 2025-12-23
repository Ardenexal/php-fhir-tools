<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRTypeRestfulInteraction
 * @description Code type wrapper for FHIRTypeRestfulInteraction enum
 */
class FHIRFHIRTypeRestfulInteractionType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTypeRestfulInteraction|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTypeRestfulInteraction|string|null $value = null,
	) {
	}
}
