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
        /** @param FHIRPermissionRuleCombining|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
