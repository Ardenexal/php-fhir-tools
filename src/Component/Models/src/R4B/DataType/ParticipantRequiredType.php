<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ParticipantRequired;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ParticipantRequired
 *
 * @description Code type wrapper for ParticipantRequired enum
 */
class ParticipantRequiredType extends CodePrimitive
{
    public function __construct(
        /** @param ParticipantRequired|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
