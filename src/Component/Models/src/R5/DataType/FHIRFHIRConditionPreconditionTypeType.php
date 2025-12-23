<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRConditionPreconditionType
 * @description Code type wrapper for FHIRConditionPreconditionType enum
 */
class FHIRFHIRConditionPreconditionTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConditionPreconditionType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConditionPreconditionType|string|null $value = null,
	) {
	}
}
