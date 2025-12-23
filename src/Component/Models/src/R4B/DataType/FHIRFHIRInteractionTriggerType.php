<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRInteractionTrigger
 * @description Code type wrapper for FHIRInteractionTrigger enum
 */
class FHIRFHIRInteractionTriggerType extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRInteractionTrigger|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRInteractionTrigger|string|null $value = null,
	) {
	}
}
