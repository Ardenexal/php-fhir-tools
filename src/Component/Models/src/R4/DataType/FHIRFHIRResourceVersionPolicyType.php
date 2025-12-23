<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRResourceVersionPolicy
 * @description Code type wrapper for FHIRResourceVersionPolicy enum
 */
class FHIRFHIRResourceVersionPolicyType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRResourceVersionPolicy|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRResourceVersionPolicy|string|null $value = null,
	) {
	}
}
