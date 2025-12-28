<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRTestReportParticipantType
 * @description Code type wrapper for FHIRTestReportParticipantType enum
 */
class FHIRTestReportParticipantTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTestReportParticipantType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTestReportParticipantType|string|null $value = null,
	) {
	}
}
