<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\CareTeamStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type CareTeamStatus
 *
 * @description Code type wrapper for CareTeamStatus enum
 */
class CareTeamStatusType extends CodePrimitive
{
    public function __construct(
        /** @param CareTeamStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
