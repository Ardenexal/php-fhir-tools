<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRConditionPreconditionType
 * @description Code type wrapper for FHIRConditionPreconditionType enum
 */
class FHIRConditionPreconditionTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRConditionPreconditionType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRConditionPreconditionType|string|null $value = null,
	) {
	}
}
