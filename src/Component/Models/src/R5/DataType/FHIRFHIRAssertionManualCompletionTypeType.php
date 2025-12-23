<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRAssertionManualCompletionType
 * @description Code type wrapper for FHIRAssertionManualCompletionType enum
 */
class FHIRFHIRAssertionManualCompletionTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAssertionManualCompletionType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAssertionManualCompletionType|string|null $value = null,
	) {
	}
}
