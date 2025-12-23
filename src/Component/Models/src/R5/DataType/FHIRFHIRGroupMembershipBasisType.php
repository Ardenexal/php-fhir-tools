<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRGroupMembershipBasis
 * @description Code type wrapper for FHIRGroupMembershipBasis enum
 */
class FHIRFHIRGroupMembershipBasisType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRGroupMembershipBasis|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRGroupMembershipBasis|string|null $value = null,
	) {
	}
}
