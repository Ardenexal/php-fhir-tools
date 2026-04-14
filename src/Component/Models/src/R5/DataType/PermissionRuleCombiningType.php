<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\PermissionRuleCombining;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type PermissionRuleCombining
 *
 * @description Code type wrapper for PermissionRuleCombining enum
 */
class PermissionRuleCombiningType extends CodePrimitive
{
    public function __construct(
        /** @param PermissionRuleCombining|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
