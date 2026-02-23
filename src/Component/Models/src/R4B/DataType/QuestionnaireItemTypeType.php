<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type QuestionnaireItemType
 * @description Code type wrapper for QuestionnaireItemType enum
 */
class QuestionnaireItemTypeType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4B\Enum\QuestionnaireItemType|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
