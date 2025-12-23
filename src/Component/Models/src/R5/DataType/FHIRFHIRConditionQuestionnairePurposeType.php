<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRConditionQuestionnairePurpose
 * @description Code type wrapper for FHIRConditionQuestionnairePurpose enum
 */
class FHIRFHIRConditionQuestionnairePurposeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConditionQuestionnairePurpose|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConditionQuestionnairePurpose|string|null $value = null,
	) {
	}
}
