<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\AppointmentResponseStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type AppointmentResponseStatus
 *
 * @description Code type wrapper for AppointmentResponseStatus enum
 */
class AppointmentResponseStatusType extends CodePrimitive
{
    public function __construct(
        /** @param AppointmentResponseStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
