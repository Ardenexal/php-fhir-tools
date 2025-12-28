<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRPermissionRuleCombining;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRPermissionRuleCombining
 *
 * @description Code type wrapper for FHIRPermissionRuleCombining enum
 */
class FHIRPermissionRuleCombiningType extends FHIRCode
{
    public function __construct(
        /** @var FHIRPermissionRuleCombining|string|null $value The code value */
        public FHIRPermissionRuleCombining|string|null $value = null,
    ) {
    }
}
