<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

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
        /** @param FHIRActionParticipantType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
