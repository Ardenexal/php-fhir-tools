<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionParticipantType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionParticipantType
 *
 * @description Code type wrapper for FHIRActionParticipantType enum
 */
class FHIRActionParticipantTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRActionParticipantType|string|null $value The code value */
        public FHIRActionParticipantType|string|null $value = null,
    ) {
    }
}
