<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTestReportParticipantType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRTestReportParticipantType
 *
 * @description Code type wrapper for FHIRTestReportParticipantType enum
 */
class FHIRFHIRTestReportParticipantTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRTestReportParticipantType|string|null $value The code value */
        public FHIRFHIRTestReportParticipantType|string|null $value = null,
    ) {
    }
}
