<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRSequenceType
 * @description Code type wrapper for FHIRSequenceType enum
 */
class FHIRSequenceTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSequenceType|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
