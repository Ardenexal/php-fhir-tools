<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRInteractionTrigger
 * @description Code type wrapper for FHIRInteractionTrigger enum
 */
class FHIRInteractionTriggerType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRInteractionTrigger|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRInteractionTrigger|string|null $value = null,
	) {
	}
}
