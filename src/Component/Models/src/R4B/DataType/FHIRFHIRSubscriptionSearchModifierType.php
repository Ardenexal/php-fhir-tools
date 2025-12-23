<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRSubscriptionSearchModifier
 * @description Code type wrapper for FHIRSubscriptionSearchModifier enum
 */
class FHIRFHIRSubscriptionSearchModifierType extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRSubscriptionSearchModifier|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRSubscriptionSearchModifier|string|null $value = null,
	) {
	}
}
