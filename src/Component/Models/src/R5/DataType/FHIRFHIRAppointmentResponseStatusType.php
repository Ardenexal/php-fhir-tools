<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRAppointmentResponseStatus
 * @description Code type wrapper for FHIRAppointmentResponseStatus enum
 */
class FHIRFHIRAppointmentResponseStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAppointmentResponseStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAppointmentResponseStatus|string|null $value = null,
	) {
	}
}
