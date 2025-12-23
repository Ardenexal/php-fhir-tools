<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRDiscriminatorType
 * @description Code type wrapper for FHIRDiscriminatorType enum
 */
class FHIRFHIRDiscriminatorTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDiscriminatorType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDiscriminatorType|string|null $value = null,
	) {
	}
}
