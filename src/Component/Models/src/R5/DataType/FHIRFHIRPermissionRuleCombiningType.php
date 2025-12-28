<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRPermissionRuleCombining;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRPermissionRuleCombining
 *
 * @description Code type wrapper for FHIRPermissionRuleCombining enum
 */
class FHIRFHIRPermissionRuleCombiningType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRPermissionRuleCombining|string|null $value The code value */
        public FHIRFHIRPermissionRuleCombining|string|null $value = null,
    ) {
    }
}
