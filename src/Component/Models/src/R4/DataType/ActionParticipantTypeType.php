<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ActionParticipantType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ActionParticipantType
 *
 * @description Code type wrapper for ActionParticipantType enum
 */
class ActionParticipantTypeType extends CodePrimitive
{
    public function __construct(
        /** @param ActionParticipantType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
