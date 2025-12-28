<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRAssertionOperatorType
 * @description Code type wrapper for FHIRAssertionOperatorType enum
 */
class FHIRAssertionOperatorTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAssertionOperatorType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAssertionOperatorType|string|null $value = null,
	) {
	}
}
