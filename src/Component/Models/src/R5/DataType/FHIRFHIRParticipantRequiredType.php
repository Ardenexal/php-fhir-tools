<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRParticipantRequired;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRParticipantRequired
 *
 * @description Code type wrapper for FHIRParticipantRequired enum
 */
class FHIRFHIRParticipantRequiredType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRParticipantRequired|string|null $value The code value */
        public FHIRFHIRParticipantRequired|string|null $value = null,
    ) {
    }
}
