<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRGroupMembershipBasis
 * @description Code type wrapper for FHIRGroupMembershipBasis enum
 */
class FHIRGroupMembershipBasisType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRGroupMembershipBasis|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRGroupMembershipBasis|string|null $value = null,
	) {
	}
}
