<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRActionParticipantType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionParticipantType
 *
 * @description Code type wrapper for FHIRActionParticipantType enum
 */
class FHIRFHIRActionParticipantTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRActionParticipantType|string|null $value The code value */
        public FHIRFHIRActionParticipantType|string|null $value = null,
    ) {
    }
}
