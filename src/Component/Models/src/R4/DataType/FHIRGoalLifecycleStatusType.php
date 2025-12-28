<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRGoalLifecycleStatus
 * @description Code type wrapper for FHIRGoalLifecycleStatus enum
 */
class FHIRGoalLifecycleStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGoalLifecycleStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGoalLifecycleStatus|string|null $value = null,
	) {
	}
}
