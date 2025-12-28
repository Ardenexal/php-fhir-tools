<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRConditionQuestionnairePurpose
 * @description Code type wrapper for FHIRConditionQuestionnairePurpose enum
 */
class FHIRConditionQuestionnairePurposeType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRConditionQuestionnairePurpose|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRConditionQuestionnairePurpose|string|null $value = null,
	) {
	}
}
