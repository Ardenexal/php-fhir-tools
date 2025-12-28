<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @var FHIRParticipantRequired|string|null $value The code value */
        public FHIRParticipantRequired|string|null $value = null,
    ) {
    }
}
