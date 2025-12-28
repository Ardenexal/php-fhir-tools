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
        /** @var FHIRDeviceCorrectiveActionScope|string|null $value The code value */
        public FHIRDeviceCorrectiveActionScope|string|null $value = null,
    ) {
    }
}
