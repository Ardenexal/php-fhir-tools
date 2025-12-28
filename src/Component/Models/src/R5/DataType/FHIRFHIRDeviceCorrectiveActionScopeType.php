<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceCorrectiveActionScope;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceCorrectiveActionScope
 *
 * @description Code type wrapper for FHIRDeviceCorrectiveActionScope enum
 */
class FHIRFHIRDeviceCorrectiveActionScopeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceCorrectiveActionScope|string|null $value The code value */
        public FHIRFHIRDeviceCorrectiveActionScope|string|null $value = null,
    ) {
    }
}
