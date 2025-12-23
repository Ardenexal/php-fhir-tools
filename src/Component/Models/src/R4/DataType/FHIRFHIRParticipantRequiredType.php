<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRParticipantRequired
 * @description Code type wrapper for FHIRParticipantRequired enum
 */
class FHIRFHIRParticipantRequiredType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRParticipantRequired|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRParticipantRequired|string|null $value = null,
	) {
	}
}
