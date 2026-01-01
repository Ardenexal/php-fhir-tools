<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRParticipantRequired;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRParticipantRequired
 *
 * @description Code type wrapper for FHIRParticipantRequired enum
 */
class FHIRParticipantRequiredType extends FHIRCode
{
    public function __construct(
        /** @param FHIRParticipantRequired|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
