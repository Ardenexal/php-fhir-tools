<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\DeviceCorrectiveActionScope;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type DeviceCorrectiveActionScope
 *
 * @description Code type wrapper for DeviceCorrectiveActionScope enum
 */
class DeviceCorrectiveActionScopeType extends CodePrimitive
{
    public function __construct(
        /** @param DeviceCorrectiveActionScope|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
