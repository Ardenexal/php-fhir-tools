<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTestReportParticipantType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTestReportParticipantType
 *
 * @description Code type wrapper for FHIRTestReportParticipantType enum
 */
class FHIRTestReportParticipantTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRTestReportParticipantType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
