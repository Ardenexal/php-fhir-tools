<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRSearchProcessingModeType
 * @description Code type wrapper for FHIRSearchProcessingModeType enum
 */
class FHIRFHIRSearchProcessingModeTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRSearchProcessingModeType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRSearchProcessingModeType|string|null $value = null,
	) {
	}
}
