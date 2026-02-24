<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\AppointmentStatus;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type AppointmentStatus
 *
 * @description Code type wrapper for AppointmentStatus enum
 */
class AppointmentStatusType extends CodePrimitive
{
    public function __construct(
        /** @param AppointmentStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
