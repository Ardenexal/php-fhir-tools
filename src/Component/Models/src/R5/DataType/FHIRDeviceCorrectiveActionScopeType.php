<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceCorrectiveActionScope;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceCorrectiveActionScope
 *
 * @description Code type wrapper for FHIRDeviceCorrectiveActionScope enum
 */
class FHIRDeviceCorrectiveActionScopeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRDeviceCorrectiveActionScope|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
