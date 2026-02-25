<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type ReportRelationshipType
 * @description Code type wrapper for ReportRelationshipType enum
 */
class ReportRelationshipTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R5\Enum\ReportRelationshipType|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
