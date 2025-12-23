<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRAssertionDirectionType
 * @description Code type wrapper for FHIRAssertionDirectionType enum
 */
class FHIRFHIRAssertionDirectionTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAssertionDirectionType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAssertionDirectionType|string|null $value = null,
	) {
	}
}
